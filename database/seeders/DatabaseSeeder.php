<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Wisata;
use App\Models\User;
use App\Models\Interaksi;

class CsvDatasetSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan Foreign Key Check sementara untuk percepatan & keamanan truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Interaksi::truncate();
        Wisata::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Import Data Wisata (wisata_indonesia.csv)
        $wisataCsvPath = base_path('dataset/wisata_indonesia.csv');
        if (file_exists($wisataCsvPath)) {
            $file = fopen($wisataCsvPath, 'r');
            $header = fgetcsv($file); // Skip baris header CSV

            while (($row = fgetcsv($file)) !== false) {
                // Pastikan indeks kolom sesuai susunan CSV
                Wisata::create([
                    'wisata_id'         => $row[0],
                    'nama_wisata'       => $row[1],
                    'kabupaten_kota'    => $row[2],
                    'provinsi'          => $row[3],
                    'kategori'          => $row[4],
                    'tags'              => $row[5],
                    'deskripsi'         => $row[6],
                    'estimasi_biaya'    => $row[7],
                    'tingkat_kesulitan' => $row[8],
                    'latitude'          => $row[9],
                    'longitude'         => $row[10],
                    'gambar'            => isset($row[11]) && !empty($row[11]) ? $row[11] : null,
                ]);
            }
            fclose($file);
            $this->command->info('Dataset wisata_indonesia.csv berhasil di-import!');
        } else {
            $this->command->error('File dataset/wisata_indonesia.csv tidak ditemukan!');
        }

        // 2. Import Data Profil Pengguna (profil_pengguna.csv)
        $userCsvPath = base_path('dataset/profil_pengguna.csv');
        if (file_exists($userCsvPath)) {
            $file = fopen($userCsvPath, 'r');
            $header = fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                User::create([
                    'user_id'              => $row[0],
                    'nama_pengguna'        => $row[1],
                    'usia'                 => $row[2],
                    'jenis_kelamin'        => $row[3],
                    'domisili'             => $row[4],
                    'minat_kategori'       => $row[5],
                    'preferensi_anggaran'  => $row[6],
                    'preferensi_kesulitan' => $row[7],
                ]);
            }
            fclose($file);
            $this->command->info('Dataset profil_pengguna.csv berhasil di-import!');
        } else {
            $this->command->error('File dataset/profil_pengguna.csv tidak ditemukan!');
        }

        // 3. Import Data Interaksi Pengguna (interaksi_pengguna_wisata.csv)
        $interaksiCsvPath = base_path('dataset/interaksi_pengguna_wisata.csv');
        if (file_exists($interaksiCsvPath)) {
            $file = fopen($interaksiCsvPath, 'r');
            $header = fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                Interaksi::create([
                    'interaksi_id'      => $row[0],
                    'user_id'           => $row[1],
                    'wisata_id'         => $row[2],
                    'jenis_interaksi'   => $row[3],
                    'rating'            => isset($row[4]) && $row[4] !== '' ? $row[4] : null,
                    'ulasan'            => isset($row[5]) && $row[5] !== '' ? $row[5] : null,
                    'tanggal_interaksi' => isset($row[6]) && $row[6] !== '' ? $row[6] : now(),
                ]);
            }
            fclose($file);
            $this->command->info('Dataset interaksi_pengguna_wisata.csv berhasil di-import!');
        } else {
            $this->command->error('File dataset/interaksi_pengguna_wisata.csv tidak ditemukan!');
        }
    }
}