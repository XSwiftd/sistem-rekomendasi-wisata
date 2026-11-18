from fastapi import FastAPI, Query
from contextlib import asynccontextmanager
from pydantic import BaseModel
from typing import List
from app.recommender import recommender_engine
from app.config import settings

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Train/Load model TF-IDF dari MySQL saat server FastAPI menyala
    recommender_engine.load_data_and_train()
    yield

app = FastAPI(
    title="NusaTripRec — Recommender Engine API",
    description="Microservice Content-Based Filtering menggunakan TF-IDF & Cosine Similarity",
    version="1.0.0",
    lifespan=lifespan
)

class RecommendationItem(BaseModel):
    wisata_id: int
    nama_wisata: str
    similarity_score: float

class RecommendationResponse(BaseModel):
    status: str
    total: int
    data: List[RecommendationItem]

@app.get("/", tags=["Health"])
def health_check():
    return {
        "status": "online",
        "service": "NusaTripRec Python Recommender Engine",
        "total_items_loaded": len(recommender_engine.df_wisata)
    }

@app.get("/recommend/user/{user_id}", response_model=RecommendationResponse, tags=["Recommendation"])
def get_user_recommendations(user_id: int, limit: int = Query(6, ge=1, le=20)):
    results = recommender_engine.recommend_for_user(user_id=user_id, top_n=limit)
    return {
        "status": "success",
        "total": len(results),
        "data": results
    }

@app.get("/recommend/item/{wisata_id}", response_model=RecommendationResponse, tags=["Recommendation"])
def get_item_recommendations(wisata_id: int, limit: int = Query(4, ge=1, le=20)):
    results = recommender_engine.recommend_by_item(wisata_id=wisata_id, top_n=limit)
    return {
        "status": "success",
        "total": len(results),
        "data": results
    }

@app.post("/recommend/reload", tags=["Admin"])
def reload_model():
    recommender_engine.load_data_and_train()
    return {
        "status": "success",
        "message": "Matriks TF-IDF & Cosine Similarity berhasil diperbarui.",
        "total_items": len(recommender_engine.df_wisata)
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("app.main:app", host="127.0.0.1", port=settings.API_PORT, reload=True)