# 🏢 HR Management System (HRMS) - PT Maju

Aplikasi sistem informasi manajemen sumber daya manusia (**HRMS**) modern berbasis **Laravel 11** untuk PT Maju. Dilengkapi dengan fitur **Absensi Berbasis Kamera Langsung (Live Selfie Webcam)**, **Otomatisasi Pengajuan & Persetujuan Hak Cuti**, serta **Manajemen Master Data Karyawan & Departemen**.

Didesain dengan antarmuka **Clean Corporate SaaS (Gusto & Rippling Style)** yang lapang, kontras tinggi, dan ramah pengguna.

---

## ✨ Fitur Unggulan

### 1. 📸 Absensi Kamera Live (Webcam Selfie Capture)
* **Live Video Stream**: Menampilkan viewport kamera depan/belakang secara *real-time* via Web Camera API browser.
* **Biometric Viewfinder**: Panduan pemosisian wajah saat pengambilan foto kehadiran.
* **Deteksi Keterlambatan Otomatis**:
  * Masuk $\le$ 08:30 WIB $\rightarrow$ Status: `Tepat Waktu` (`present`).
  * Masuk > 08:30 WIB $\rightarrow$ Status: `Terlambat` (`late`).
* **Pencegahan Absen Ganda**: Mencegah multiple clock-in pada hari kerja yang sama.
* **Verifikasi HRD**: Admin HRD dapat melihat dan memverifikasi foto bukti kehadiran (Clock-In & Clock-Out) dalam modal beresolusi penuh.

### 2. 🏖️ Manajemen & Persetujuan Hak Cuti
* **Perhitungan Durasi Otomatis**: Sistem menghitung total hari kerja yang diajukan.
* **Validasi Sisa Kuota**: Mencegah pengajuan melebihi sisa kuota cuti tahunan (default 12 hari/tahun).
* **Auto-Deduct Quota**: Sisa kuota cuti karyawan otomatis berkurang saat disetujui (*Approve*) oleh Admin HRD.
* **Catatan Penolakan**: HRD dapat memberikan alasan penolakan saat menolak (*Reject*) permohonan.

### 3. 👥 Master Data Karyawan & Departemen
* **CRUD Data Karyawan**: NIK unik, nama, email, jabatan, departemen, tanggal bergabung (*join date*), gaji pokok, dan kuota cuti.
* **Struktur Departemen**: Pengelompokan divisi unit kerja dengan counter jumlah staf aktif.

### 4. 🔐 Autentikasi & Multi-Role Guard
* Mendukung login menggunakan **NIK atau Alamat Email** + Password.
* Role-based access control (`admin_hr` vs `employee`).
* Tombol **1-Click Demo Login** untuk kemudahan pengujian.

---

## 🛠️ Tech Stack

* **Framework:** Laravel 11 (PHP 8.2+)
* **Database:** SQLite (Default plug-and-play) & MySQL / MariaDB (XAMPP ready)
* **Frontend:** Blade Templating, Tailwind CSS, FontAwesome 6, SweetAlert2
* **Device API:** HTML5 Web Camera API (`navigator.mediaDevices.getUserMedia`) & Canvas API

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Clone Repositori
```bash
git clone https://github.com/username/hrms.git
cd hrms
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file konfigurasi environment:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database & Jalankan Seeder

**Opsi A: SQLite (Instan tanpa perlu setup server database)**
```bash
php artisan migrate:fresh --seed
```

**Opsi B: MySQL (XAMPP / phpMyAdmin)**
1. Buat database baru bernama `hrms_db` di phpMyAdmin.
2. Sesuaikan konfigurasi di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hrms_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate:fresh --seed
   ```

### 5. Hubungkan Storage Link (Wajib untuk Foto Absensi)
```bash
php artisan storage:link
```

### 6. Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui browser di: **[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Kredensial Akun Pengujian (Demo Login)

| Peran (Role) | Email / NIK | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin HRD** | `admin@hrms.local` / `HR001` | `password` | Dashboard KPI, Log Foto Absensi, Approval Cuti, CRUD Karyawan & Dept |
| **Karyawan 1 (IT)** | `budi@hrms.local` / `EMP001` | `password` | Absensi Kamera Live, Pengajuan Cuti, Riwayat Kehadiran |
| **Karyawan 2 (Finance)** | `siti@hrms.local` / `EMP002` | `password` | Akses Portal Karyawan Departemen Finance |
| **Karyawan 3 (Operations)**| `andi@hrms.local` / `EMP003` | `password` | Akses Portal Karyawan Departemen Operasional |

---

## 📂 Struktur Database Utama

* `departments` - Data departemen / divisi unit kerja.
* `users` - Data akun pengguna, NIK, jabatan, gaji, dan kuota cuti.
* `attendances` - Data riwayat absensi harian, jam masuk/keluar, status kehadiran, dan path file foto selfie (`image_in`, `image_out`).
* `leave_requests` - Data pengajuan cuti, tanggal mulai/selesai, total hari, alasan, status (`pending`/`approved`/`rejected`), dan catatan admin.

---

## 📄 Lisensi
Hak Cipta &copy; 2026 PT Maju. Dikembangkan untuk kebutuhan operasional internal perusahaan.
