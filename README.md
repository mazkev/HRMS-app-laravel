# 🏢 HR Management System (HRMS) - PT Maju (Enterprise Suite)

Aplikasi sistem informasi manajemen sumber daya manusia (**HRMS Enterprise Suite**) modern berbasis **Laravel 11** untuk PT Maju. Dirancang dengan standar industri untuk kebutuhan operasional lengkap: **Absensi Kamera & Verifikasi GPS Geofencing**, **Modul Penggajian (Payroll & Slip Gaji Otomatis)**, **Manajemen Lembur (Overtime Tracker)**, **Kalender Cuti Tim**, serta **Ekspor Rekapitulasi Data ke CSV/Excel**.

Didesain dengan antarmuka **Clean Corporate SaaS (Gusto & Rippling Style)** yang lapang, kontras tinggi, elegan, dan sangat intuitif.

---

## ✨ Fitur-Fitur Utama (Enterprise Suite)

### 1. 📸 Absensi Kamera Live & GPS Geofencing
* **Live Video Stream**: Menampilkan viewport kamera webcam biometrik secara *real-time* via Web Camera API browser.
* **GPS Radius Geofencing**: Mengambil koordinat GPS perangkat karyawan dan menghitung jarak aktual ke kantor pusat menggunakan **Haversine formula** (Radius default: 250m).
* **Deteksi Keterlambatan Otomatis**:
  * Masuk $\le$ 08:30 WIB $\rightarrow$ Status: `Tepat Waktu` (`present`).
  * Masuk > 08:30 WIB $\rightarrow$ Status: `Terlambat` (`late`).
* **Pencegahan Absen Ganda**: Mencegah multiple clock-in pada hari kerja yang sama.
* **Verifikasi HRD**: Admin HRD dapat melihat foto bukti kehadiran (Clock-In & Clock-Out) dan jarak koordinat GPS per log absensi.

### 2. 💰 Modul Penggajian & Cetak Slip Gaji (Payroll)
* **Kalkulasi Gaji Otomatis**: Mengintegrasikan gaji pokok karyawan, tunjangan tetap, serta denda keterlambatan otomatis berdasarkan data absensi harian.
* **Generate Payroll Bulanan**: Admin HRD dapat mengkalkulasi payroll seluruh staf hanya dalam 1-klik.
* **Slip Gaji Resmi (Printable & PDF Ready)**: Karyawan dan HRD dapat mencetak atau mengunduh dokumen slip gaji berformat resmi lengkap dengan kop surat PT Maju Nusantara dan kolom tanda tangan.

### 3. ⏱️ Modul Pengajuan & Persetujuan Lembur (Overtime)
* **Form Pengajuan Lembur**: Karyawan dapat mengajukan jam lembur dengan kalkulasi durasi jam otomatis dan justifikasi tugas.
* **Alur Approval HRD**: Admin HRD dapat meninjau kesesuaian lembur dengan riwayat jam pulang aktual untuk persetujuan atau penolakan.

### 4. 🏖️ Manajemen Cuti & Kalender Cuti Bersama
* **Validasi Sisa Kuota Real-Time**: Mencegah pengajuan melebihi sisa hak cuti tahunan (default 12 hari/tahun).
* **Auto-Deduct Kuota**: Pemotongan kuota cuti secara otomatis saat disetujui (*Approve*) oleh HRD.
* **Kalender Cuti Bersama (Team Leave Calendar)**: Kalender visual interaktif bulanan untuk memantau ketersediaan rekan kerja di seluruh divisi.

### 5. 📊 Ekspor Laporan Data (CSV / Excel)
* Download rekap data log absensi harian dan rekap penggajian bulanan dalam format CSV/Excel dengan satu klik.

---

## 🛠️ Tech Stack

* **Framework:** Laravel 11 (PHP 8.2+)
* **Database:** SQLite (Default plug-and-play) & MySQL / MariaDB (XAMPP ready)
* **Frontend:** Blade Templating, Tailwind CSS, FontAwesome 6, SweetAlert2
* **Device APIs:** HTML5 Web Camera API, Geolocation API, Canvas API

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Clone Repositori
```bash
git clone https://github.com/mazkev/HRMS-app-laravel.git
cd HRMS-app-laravel
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database & Jalankan Seeder
```bash
php artisan migrate:fresh --seed
```

*(Opsional: Jika menggunakan MySQL di XAMPP, sesuaikan `DB_CONNECTION=mysql` dan nama database di `.env` lalu jalankan perintah di atas).*

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

| Peran (Role) | Email / NIK | Password | Akses Fitur Utama |
| :--- | :--- | :--- | :--- |
| **Admin HRD** | `admin@hrms.local` / `HR001` | `password` | Overview Dashboard, Log Absensi GPS, Approval Cuti & Lembur, Payroll & Slip Gaji, Kalender Tim, Export CSV, CRUD Karyawan & Dept |
| **Karyawan 1 (IT)** | `budi@hrms.local` / `EMP001` | `password` | Dashboard Personal, Absensi Kamera + GPS, Pengajuan Cuti, Pengajuan Lembur, Cetak Slip Gaji Saya, Kalender Tim |
| **Karyawan 2 (Finance)** | `siti@hrms.local` / `EMP002` | `password` | Portal Karyawan Departemen Finance |
| **Karyawan 3 (Operations)**| `andi@hrms.local` / `EMP003` | `password` | Portal Karyawan Departemen Operasional |

---

## 📄 Lisensi
Hak Cipta &copy; 2026 PT Maju Nusantara. Dikembangkan untuk efisiensi operasional sumber daya manusia modern.
