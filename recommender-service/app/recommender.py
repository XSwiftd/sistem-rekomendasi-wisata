import pandas as pd
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from sqlalchemy import text
from app.database import engine
import logging

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class ContentBasedRecommender:
    def __init__(self):
        self.df_wisata = pd.DataFrame()
        self.tfidf_matrix = None
        self.cosine_sim = None
        self.vectorizer = TfidfVectorizer(stop_words=None)

    def load_data_and_train(self):
        """Membaca data dari MySQL & menghitung matriks TF-IDF & Cosine Similarity."""
        logger.info("Memuat data dari database MySQL...")
        query = """
            SELECT 
                wisata_id, nama_wisata, deskripsi, kategori, 
                provinsi, kabupaten_kota, tingkat_kesulitan, tags
            FROM wisatas
        """
        try:
            with engine.connect() as conn:
                self.df_wisata = pd.read_sql(text(query), conn)
        except Exception as e:
            logger.error(f"Gagal mengambil data dari MySQL: {e}")
            return

        if self.df_wisata.empty:
            logger.warning("Data wisata di database masih kosong!")
            return

        # Preprocessing & Penggabungan Fitur Teks
        self.df_wisata['content_features'] = (
            self.df_wisata['nama_wisata'].fillna('') + ' ' +
            self.df_wisata['kategori'].fillna('') + ' ' +
            self.df_wisata['deskripsi'].fillna('') + ' ' +
            self.df_wisata['tags'].fillna('') + ' ' +
            self.df_wisata['kabupaten_kota'].fillna('') + ' ' +
            self.df_wisata['provinsi'].fillna('')
        ).str.lower()

        # Matriks TF-IDF
        logger.info("Membangun matriks TF-IDF...")
        self.tfidf_matrix = self.vectorizer.fit_transform(self.df_wisata['content_features'])

        # Matriks Cosine Similarity
        logger.info("Menghitung Matriks Cosine Similarity...")
        self.cosine_sim = cosine_similarity(self.tfidf_matrix, self.tfidf_matrix)
        logger.info(f"Recommender Engine Siap! Total data: {len(self.df_wisata)} wisata.")

    def recommend_by_item(self, wisata_id: int, top_n: int = 6):
        """Rekomendasi Wisata Serupa (Item-to-Item Similarity)."""
        if self.df_wisata.empty or self.cosine_sim is None:
            return []

        if wisata_id not in self.df_wisata['wisata_id'].values:
            return []

        idx = self.df_wisata[self.df_wisata['wisata_id'] == wisata_id].index[0]
        sim_scores = list(enumerate(self.cosine_sim[idx]))
        sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
        sim_scores = [item for item in sim_scores if item[0] != idx][:top_n]

        recommendations = []
        for i, score in sim_scores:
            row = self.df_wisata.iloc[i]
            recommendations.append({
                "wisata_id": int(row['wisata_id']),
                "nama_wisata": str(row['nama_wisata']),
                "similarity_score": round(float(score), 4)
            })

        return recommendations

    def recommend_for_user(self, user_id: int, top_n: int = 6):
        """Rekomendasi Personal User Profile (User Profile Vector vs TF-IDF Matrix)."""
        if self.df_wisata.empty or self.tfidf_matrix is None:
            return []

        try:
            # Ambil interaksi user dari tabel interaksis saja (Rating >= 3.0)
            query = text("""
                SELECT wisata_id, rating 
                FROM interaksis 
                WHERE user_id = :u_id AND rating >= 3.0
            """)

            with engine.connect() as conn:
                user_history = pd.read_sql(query, conn, params={"u_id": user_id})

            # Jika user belum memiliki riwayat interaksi
            if user_history.empty:
                logger.info(f"User #{user_id} belum memiliki riwayat interaksi di tabel interaksis.")
                return []

            history_ids = user_history['wisata_id'].tolist()
            history_indices = self.df_wisata[self.df_wisata['wisata_id'].isin(history_ids)].index.tolist()

            if not history_indices:
                return []

            # Hitung Rata-rata Vektor TF-IDF dari wisata yang disukai user
            user_profile_vector = np.asarray(self.tfidf_matrix[history_indices].mean(axis=0))

            # Hitung Similarity dengan seluruh katalog
            user_sim_scores = cosine_similarity(user_profile_vector, self.tfidf_matrix).flatten()

            sim_scores_list = []
            for idx, score in enumerate(user_sim_scores):
                w_id = self.df_wisata.iloc[idx]['wisata_id']
                if w_id not in history_ids:
                    sim_scores_list.append((idx, score))

            sim_scores_list = sorted(sim_scores_list, key=lambda x: x[1], reverse=True)[:top_n]

            results = []
            for i, score in sim_scores_list:
                row = self.df_wisata.iloc[i]
                results.append({
                    "wisata_id": int(row['wisata_id']),
                    "nama_wisata": str(row['nama_wisata']),
                    "similarity_score": round(float(score), 4)
                })

            return results
        except Exception as e:
            logger.error(f"Error pada recommend_for_user: {e}")
            return []

# Singleton Instance
recommender_engine = ContentBasedRecommender()