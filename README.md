# PDF Management System (Headless API)

## Deskripsi Project

PDF Management System adalah aplikasi **Headless API** yang dibangun menggunakan framework **Laravel 12**. Sistem ini dirancang khusus untuk menangani pengelolaan dokumen dan pembuatan laporan PDF.

Proyek ini dibangun dengan menerapkan **Strict Layered Architecture** (Controller, Service, dan Repository) untuk memastikan kode tetap **bersih (clean)**, **mudah dibaca (readable)**, dan mematuhi prinsip **best practices** dalam pengembangan perangkat lunak (SOLID Principles). Pemisahan tanggung jawab (Separation of Concerns) ini memudahkan pengujian dan pemeliharaan jangka panjang.

Meskipun merupakan Headless API, proyek ini menggunakan satu template **Blade** khusus untuk keperluan pembuatan laporan PDF menggunakan library DOMPDF. Template ini dibuat menggunakan HTML/CSS.

## Tech Stack

Teknologi utama yang digunakan dalam pengembangan proyek ini meliputi:

**Backend:**

- **PHP** (^8.2)
- **Laravel Framework** (^12.0)
- **Laravel Sanctum** (Autentikasi API)
- **barryvdh/laravel-dompdf** (Pembuatan file PDF)

**Database:**

- **PostgreSQL** (Default Database)

**Templating (PDF Only):**

- **Blade Templates** (Raw HTML/CSS untuk layout laporan)

**Tools:**

- **Composer** (Dependency manager)

## Cara Instalasi

Ikuti langkah-langkah berikut untuk menginstal proyek di lingkungan lokal Anda:

### Prasyarat

Pastikan Anda telah menginstal:

- PHP >= 8.2
- Composer
- PostgreSQL Database
- Node.js & NPM (untuk build script dasar)

### Langkah Instalasi

1. **Clone repository**

   ```bash
   git clone https://github.com/irfnhanif/pdf-management-system.git
   cd pdf-management-system
   ```

2. **Jalankan Setup Otomatis**
   Perintah ini akan menginstal dependensi, menyalin file `.env`, dan generate key.

   ```bash
   composer run setup
   ```

3. **Konfigurasi Database**
   Buka file `.env` yang baru dibuat, dan sesuaikan konfigurasi database untuk menggunakan **PostgreSQL**:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=nama_database_anda
   DB_USERNAME=username_postgres_anda
   DB_PASSWORD=password_postgres_anda
   ```

4. **Migrasi Database**
   Setelah konfigurasi `.env` sesuai, jalankan migrasi:
   ```bash
   php artisan migrate
   ```

## Cara Menjalankan Project

Untuk menjalankan aplikasi di lingkungan lokal, gunakan perintah berikut:

```bash
composer run dev
```

Perintah ini akan menjalankan server lokal Laravel dan proses background yang diperlukan secara bersamaan. API akan dapat diakses melalui `http://localhost:8000`.

## Struktur Folder & Arsitektur

Proyek ini menggunakan **Strict Layered Architecture** untuk memisahkan logika bisnis dari penanganan request HTTP. Berikut adalah struktur folder utamanya:

```
pdf-management-system/
├── app/
│   ├── Http/
│   │   └── Controllers/    # Layer 1: Menerima input, validasi, dan memanggil Service
│   ├── Services/           # Layer 2: Berisi logika bisnis (Business Logic) utama
│   ├── Repositories/       # Layer 3: Abstraksi query database (Data Access Layer)
│   └── Models/             # Representasi data (Eloquent ORM)
├── bootstrap/              # Skrip startup framework
├── config/                 # File konfigurasi aplikasi
├── database/               # Migrasi schema PostgreSQL, factories, dan seeders
├── resources/
│   └── views/              # Template Blade (Khusus untuk layout PDF)
├── routes/                 # Definisi routing API (api.php)
├── storage/                # Penyimpanan file PDF yang dihasilkan
├── .env.example            # Contoh konfigurasi environment
├── composer.json           # Definisi dependensi PHP
└── README.md               # Dokumentasi proyek
```

### Penjelasan Arsitektur

1.  **Controller**: Hanya bertugas menerima request HTTP, memvalidasi input, memanggil method di _Service_, dan mengembalikan respon JSON (Response Formatting). Controller tidak boleh mengandung logika bisnis yang kompleks atau query database langsung.
2.  **Service**: Tempat di mana logika bisnis utama berada. Service menerima data dari Controller, melakukan pemrosesan, dan berinteraksi dengan _Repository_ jika membutuhkan data.
3.  **Repository**: Bertanggung jawab penuh atas akses data ke database. Service tidak boleh memanggil Model Eloquent secara langsung, melainkan harus melalui Repository.
