<div align="center">

  <img src="https://cdn-icons-png.flaticon.com/512/3212/3212608.png" alt="logo" width="120" height="auto" />
  
  # 🌍 BUMI EXPLORER
  
  **Platform Edukasi Seputar Bumi dan Gamifikasi Berbasis Web**
  
  [![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel)](https://laravel.com)
  [![Filament](https://img.shields.io/badge/Filament-3.x-orange?style=for-the-badge&logo=livewire)](https://filamentphp.com)
  [![Docker](https://img.shields.io/badge/Docker-Ready-blue?style=for-the-badge&logo=docker)](https://www.docker.com/)
  [![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

  <p class="description">
    Sebuah Learning Management System (LMS) interaktif yang dirancang khusus untuk siswa SD & SMP. Menggabungkan materi sains antariksa dengan elemen <i>gamification</i> yang menyenangkan.
  </p>

  [Fitur Utama](#-fitur-unggulan) •
  [Teknologi](#-teknologi) •
  [Instalasi](#-instalasi-via-docker) •
  [Screenshots](#-galeri-antarmuka)

</div>

---

## 📖 Tentang Proyek

**Bumi Explorer** bukan sekadar website sekolah biasa. Proyek ini bertujuan memecahkan masalah sistem login yang rumit bagi anak-anak dan membuat materi pelajaran yang membosankan menjadi petualangan interaktif. 

Dibangun dengan **Laravel** dan panel admin super cepat **FilamentPHP**, aplikasi ini sepenuhnya ter-kontainerisasi menggunakan **Docker** untuk kemudahan pengembangan.

## 🚀 Fitur Unggulan

### 🛡️ 1. Login Visual Tanpa Password (Zero-Friction)
Sistem keamanan psikologis yang ramah anak. Tidak ada password teks yang rumit!
* **Siswa Baru:** Mendaftar hanya dengan nama & memilih **"Secret Badge"** (ikon rahasia seperti 🐱, 🚀, ⚽).
* **Login Kembali:** Siswa mengetik nama, lalu ditantang menebak ikon rahasia mereka di antara gambar pengecoh.
* **Anti-Imposter:** Mencegah siswa lain masuk sembarangan menggunakan nama temannya.

### 📚 2. Mode Cerita Interaktif (Story Mode)
Materi pelajaran disajikan dalam bentuk slide carousel bergaya **Hologram**.
* Dilengkapi Visualisasi Gambar.
* Tampilan UI Glassmorphism (Kaca Transparan) bertema *Dark Space*.

### 🎮 3. Kuis Hybrid (Swipe & Tap)
Uji kompetensi dengan gaya permainan modern:
* **Soal Pilihan Ganda:** Tombol interaktif dengan efek visual benar/salah.
* **Soal Benar/Salah:** Menggunakan mekanik **Swipe Card** (Geser Kanan/Kiri) ala bermain kartu.

### 🏆 4. Hall of Fame (Leaderboard)
Papan peringkat kompetitif untuk memotivasi siswa.
* **Podium 3D:** Tampilan khusus untuk Juara 1, 2, dan 3.
* **Avatar Unik:** Setiap siswa mendapatkan avatar kartun otomatis.

### ⚙️ 5. Admin Panel Canggih (Filament)
Guru memiliki kendali penuh melalui dashboard modern:
* Manajemen Siswa & Reset Akun.
* CRUD Materi & Slide (Repeater Field).
* Bank Soal & Monitoring Nilai.

---

## 🛠 Teknologi

Project ini dibangun menggunakan *stack* teknologi modern:

| Komponen | Teknologi |
| :--- | :--- |
| **Framework** | Laravel 10 / 11 |
| **Admin Panel** | FilamentPHP v3 |
| **Database** | MySQL 8.0 |
| **Environment** | Docker (Laravel Sail) |
| **Frontend** | Blade, Bootstrap 5, Custom CSS Animations |
| **Interactivity** | Alpine.js, Vanilla JS (Swipe Logic) |
| **Styling** | Animate.css, Bootstrap Icons |

---

## 🐳 Instalasi (Via Docker)

Pastikan **Docker Desktop** atau **Docker Engine** sudah terinstall.

**1. Clone Repository**
```bash
git clone [https://github.com/muhammadfathanfuad/earth-explorer-academy.git](https://github.com/muhammadfathanfuad/earth-explorer-academy.git)
cd earth-explorer-academy
```

**2. Setup Environment**

```bash
cp .env.example .env

```

*(Sesuaikan konfigurasi database di .env agar cocok dengan `docker-compose.yml` Anda, misalnya DB_HOST=mysql)*

**3. Jalankan Container**
Bangun dan jalankan container di background:

```bash
docker compose up -d --build

```

**4. Install Dependencies (Composer)**
Masuk ke container aplikasi untuk menginstall library PHP:

```bash
docker compose exec app composer install

```

*(Catatan: Ganti `app` dengan nama service PHP Anda di docker-compose.yml jika berbeda)*

**5. Setup Aplikasi**
Jalankan perintah ini untuk generate key, migrasi database, dan link storage:

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan storage:link

```

**6. Selesai!**
Akses aplikasi melalui browser:

* **URL:** `http://localhost` (atau port yang Anda definisikan, misal: `http://localhost:8000`)

---

## 🔑 Akun Demo (Seeder)

Jika Anda menjalankan `migrate --seed`, gunakan akun berikut:

**Administrator / Guru:**

* **Email:** `admin@admin.com`
* **Password:** `password`

**Siswa:**

* Silakan daftar langsung di halaman depan menggunakan fitur *Self-Service Registration*.

---

## 📸 Galeri Antarmuka

*(Tempatkan screenshot aplikasi Anda di sini untuk memukau pengunjung repository)*

| Login Visual | Story Mode |
| --- | --- |
| <img src="path/to/screenshot_login.png" alt="Login" width="400"/> | <img src="path/to/screenshot_materi.png" alt="Materi" width="400"/> |

| Swipe Quiz | Leaderboard |
| --- | --- |
| <img src="path/to/screenshot_swipe.png" alt="Quiz" width="400"/> | <img src="path/to/screenshot_rank.png" alt="Rank" width="400"/> |

---

## 🤝 Kontribusi

Tertarik mengembangkan proyek ini? Silakan:

1. Fork repository ini.
2. Buat branch fitur baru (`git checkout -b fitur-keren`).
3. Commit perubahan Anda (`git commit -m 'Menambah fitur keren'`).
4. Push ke branch (`git push origin fitur-keren`).
5. Buat Pull Request.

---

<div align="center">
Dibuat dengan ❤️ dan ☕ oleh <b>Muhammad Fathan Fuad</b>
</div>
