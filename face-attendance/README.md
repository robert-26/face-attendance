# Sistem Absensi Mahasiswa Berbasis Face Recognition

## Struktur Folder

face-attendance/
├── backend/ (Laravel)
├── face_recognition/
│   ├── dataset/
│   ├── trainer/
│   ├── scripts/
│   └── utils/
├── storage/
│   └── attendance/

## Database

Gunakan file `database.sql` untuk membuat database dan tabel.

## Backend Laravel

File utama:
- `backend/routes/web.php`
- `backend/app/Http/Controllers/AuthController.php`
- `backend/app/Http/Controllers/MahasiswaController.php`
- `backend/app/Http/Controllers/AbsensiController.php`
- `backend/resources/views/*`

## Python Face Recognition

File utama:
- `face_recognition/scripts/capture.py`
- `face_recognition/scripts/train.py`
- `face_recognition/scripts/recognize.py`
- `face_recognition/utils/db.py`

## Cara Menjalankan

1. Install dependency:
   - Buka terminal di folder `face-attendance/backend`
   - Jalankan `composer install`
   - Install Python packages: `pip install opencv-python face_recognition numpy mysql-connector-python`
2. Setup database MySQL:
   - Jalankan skrip `database.sql` di server MySQL Anda.
   - Atau gunakan MySQL client untuk membuat database dan tabel.
3. Konfigurasi Laravel:
   - Salin `backend/.env.example` menjadi `backend/.env`
   - Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.
4. Konfigurasi Python:
   - Pastikan environment variable optional bila berbeda:
     - `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`
   - Default menggunakan `127.0.0.1`, user `root`, database `face_attendance`.
5. Jalankan backend Laravel:
   - `cd face-attendance/backend`
   - `php artisan serve --host=127.0.0.1 --port=8000`
6. Jalankan model training jika sudah ada dataset:
   - `cd face-attendance/face_recognition/scripts`
   - `python train.py`
7. Jalankan absensi realtime:
   - `cd face-attendance/face_recognition/scripts`
   - `python recognize.py`

## Proses absensi

1. Login admin di `http://127.0.0.1:8000/login`
   - email: `admin@kampus.com`
   - password: `admin123`
2. Tambah mahasiswa di menu `Mahasiswa`.
3. Ambil dataset wajah dengan tombol `Ambil Dataset`.
4. Jalankan `train.py`.
5. Jalankan `recognize.py` dan biarkan mahasiswa melakukan absensi di depan webcam.
