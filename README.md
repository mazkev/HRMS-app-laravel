# 🏢 HR Management System (HRMS) - PT Maju (Next-Gen Ultimate Suite)

Aplikasi sistem informasi manajemen sumber daya manusia (**HRMS Next-Gen Ultimate Suite**) kelas enterprise berbasis **Laravel 11** untuk PT Maju Nusantara. 

Dirancang secara komprehensif untuk mengotomatisasi seluruh siklus operasional HR modern: **Absensi Kamera Biometrik & GPS Geofencing**, **Klaim Reimbursement & Auto-Payroll**, **Penilaian Kinerja (KPI Appraisal & Scorecard)**, **Papan Pengumuman Digital**, **Brankas Dokumen Karyawan**, **Manajemen Multi-Shift Kerja**, serta dukungan **Progressive Web App (PWA)**.

Didesain dengan antarmuka **Clean Corporate SaaS (Gusto & Rippling Style)** yang lapang, kontras tinggi, bebas *AI slop*, dan sangat intuitif.

---

## ✨ Fitur-Fitur Komprehensif (Next-Gen Suite)

### 1. 📸 Absensi Kamera Live & GPS Geofencing
* **Live Biometric Viewfinder**: Viewport kamera webcam dengan panduan frame wajah secara *real-time*.
* **GPS Radius Geofencing**: Mengambil koordinat GPS perangkat karyawan dan menghitung jarak aktual ke kantor pusat menggunakan **Haversine formula** (Radius default: 250m).
* **Deteksi Keterlambatan Otomatis**: Menyesuaikan batas waktu keterlambatan secara dinamis per shift kerja.
* **Verifikasi HRD**: Admin HRD dapat melihat foto bukti kehadiran (Clock-In & Clock-Out) dan jarak koordinat GPS.

### 2. 🧾 Klaim Biaya Operasional & Medis (Reimbursement)
* **Lampiran Bukti Struk/Kuitansi**: Karyawan dapat mengunggah foto nota/struk pengeluaran (transport dinas, medis, makan, ATK).
* **Alur Review & Approval**: HR Finance dapat memverifikasi kuitansi dan menyetujui/menolak klaim.

### 3. 🏆 Penilaian Kinerja & KPI (Performance Appraisal)
* **Scoring Multi-Dimensi**: Evaluasi berkala (Q1, Q2, Q3, Q4, Annual) dengan pembobotan otomatis: KPI (50%), Absensi (30%), Teamwork (20%).
* **Predikat Nilai (Grade A, B, C, D)**: Rapor evaluasi kinerja transparan bagi karyawan dan manajemen.

### 4. 💰 Modul Penggajian & Cetak Slip Gaji (Payroll)
* **Auto-Kalkulasi Gaji**: Menghitung gaji bersih otomatis berdasarkan gaji pokok + tunjangan dikurangi denda keterlambatan.
* **Slip Gaji Resmi (Printable & PDF Ready)**: Dokumen slip gaji berformat resmi lengkap dengan kop surat PT Maju Nusantara dan kolom tanda tangan.

### 5. 📢 Papan Pengumuman & Berkas Dokumen (Bulletin & Vault)
* **Papan Pengumuman Internal**: Siaran memo resmi HRD, info libur nasional, dan kebijakan kantor dengan fitur *Pin to Top*.
* **Brankas Dokumen Karyawan (Digital Vault)**: Tempat penyimpanan aman berkas identitas (KTP, NPWP, PKWT, sertifikat).

### 6. 🔄 Manajemen Shift Kerja (Multi-Shift & Roster)
* Pengaturan shift kerja fleksibel (*Regular Office*, *Shift Pagi*, *Shift Siang*) dengan toleransi jam masuk dinamis.

### 7. 📱 Progressive Web App (PWA) Support
* Karyawan dapat menginstal aplikasi HRMS langsung ke layar utama smartphone (Android/iOS) layaknya aplikasi native dengan dukungan offline caching.

---

## 🛠️ Tech Stack

* **Framework:** Laravel 11 (PHP 8.2+)
* **Database:** SQLite (Default plug-and-play) & MySQL / MariaDB (XAMPP ready)
* **Frontend:** Blade Templating, Tailwind CSS, FontAwesome 6, SweetAlert2
* **Device APIs:** HTML5 Web Camera API, Geolocation API, Service Worker (PWA), Canvas API

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

### 5. Hubungkan Storage Link (Wajib untuk Foto Absensi & Kuitansi)
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
| **Admin HRD** | `admin@hrms.local` / `HR001` | `password` | Dashboard KPI, Log Absensi GPS, Approval Cuti & Lembur, Reimbursement, Penilaian KPI, Payroll, Kalender Tim, Pengumuman, Brankas Dokumen, Shift & Karyawan |
| **Karyawan 1 (IT)** | `budi@hrms.local` / `EMP001` | `password` | Dashboard Personal, Absensi Kamera + GPS, Pengajuan Cuti, Lembur, Klaim Reimburse, Rapor KPI, Slip Gaji, Kalender Tim, Pengumuman & Berkas |
| **Karyawan 2 (Finance)** | `siti@hrms.local` / `EMP002` | `password` | Portal Karyawan Departemen Finance |
| **Karyawan 3 (Operations)**| `andi@hrms.local` / `EMP003` | `password` | Portal Karyawan Departemen Operasional (Shift Pagi) |

---

## 📄 Lisensi
Hak Cipta &copy; 2026 PT Maju Nusantara. Next-Gen Enterprise Edition.
