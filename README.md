# 🏢 HR Management System (HRMS) - PT Maju (Premier Global Enterprise Suite)

Aplikasi sistem informasi manajemen sumber daya manusia (**HRMS Premier Global Enterprise**) terlengkap dan modern berbasis **Laravel 11** untuk PT Maju Nusantara. 

Mencakup seluruh siklus operasional HR harian, kepatuhan regulasi ketenagakerjaan Indonesia, analitik eksekutif, serta keterlibatan karyawan (*employee engagement*):
* **🕌 Tunjangan Hari Raya (THR Keagamaan) & Cetak Slip THR Resmi (Formula Pro-Rata Kemnaker RI)**
* **✈️ Surat Perintah Perjalanan Dinas (SPPD) & Kalkulasi Uang Saku Harian (*Per Diem*)**
* **🔄 Pengajuan Tukar Shift Antar Karyawan (*Shift Swap Request*)**
* **📲 WhatsApp & Email Notification Gateway Simulator (*HR Dispatcher*)**
* **🤝 Peer Recognition (Kudos) & Papan Apresiasi (*Wall of Fame*)**
* **🇮🇩 Kepatuhan Pajak PPh 21 (TER 2024) & Kalkulator BPJS (Kesehatan 1% + Ketenagakerjaan 3%)**
* **⚠️ Surat Peringatan Kedisiplinan (SP 1, SP 2, SP 3) & Dokumen Hukum Resmi**
* **🚪 Manajemen Resignasi & Surat Pengalaman Kerja (Paklaring)**
* **🏥 Cuti Khusus (Melahirkan, Menikah, Duka) & Wajib Upload Surat Dokter (SKD)**
* **🤖 Asisten Virtual HR (AI Policy Helpdesk Chatbot 24/7)**
* **📊 Executive Analytics Dashboard (Line, Donut, Bar Chart via Chart.js)**
* **🌳 Struktur Organisasi Interaktif (Interactive Org Chart)**
* **📸 Absensi Kamera Biometrik & GPS Geofencing (Radius 250m Haversine)**
* **🏢 Rekrutmen & Pelacakan Pelamar (ATS) dengan 1-Click Convert to Employee**
* **💻 Manajemen Inventaris & Aset Kantor (Asset Tracker)**
* **💸 Pinjaman Karyawan & Kasbon Darurat (Auto-Deduct Payroll)**
* **🧾 Klaim Biaya Operasional (Reimbursement)**
* **🏆 Penilaian Kinerja Multi-Dimensi (KPI Appraisal)**
* **🎓 Pelatihan Karyawan & Self-Enrollment (LMS Lite)**
* **💰 Penggajian & Cetak Slip Gaji PDF Resmi (Payroll)**
* **🛡️ Audit Trail Log & Keamanan Sistem**
* **📱 Progressive Web App (PWA) Ready**

Didesain dengan standar antarmuka **Clean Corporate SaaS (Gusto & Rippling Style)** yang lapang, elegan, kontras tinggi, dan bebas *AI slop*.

---

## 🛠️ Tech Stack

* **Framework:** Laravel 11 (PHP 8.2+)
* **Database:** SQLite (Default plug-and-play) & MySQL / MariaDB (XAMPP ready)
* **Frontend:** Blade Templating, Tailwind CSS, FontAwesome 6, Chart.js, SweetAlert2
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

### 5. Hubungkan Storage Link
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
| **Admin HRD** | `admin@hrms.local` / `HR001` | `password` | Dashboard Eksekutif, Analytics Chart.js, Org Chart, Gateway WA/Email, THR Generator, Approval SPPD, Tukar Shift, SP, Resignasi & Paklaring, Absensi GPS, Approval Cuti & SKD, ATS Rekrutmen, Asset Tracker, Kasbon, KPI, Pelatihan LMS, Payroll, Audit Log |
| **Karyawan 1 (IT)** | `budi@hrms.local` / `EMP001` | `password` | Dashboard Personal, AI Helpdesk Bot, Absensi Kamera + GPS, SPPD Surabaya, Slip THR, Kudos Wall of Fame, Cuti, Lembur, Reimburse, Kasbon, Aset Laptop, Rapor KPI, Slip Gaji, Pelatihan, Brankas Dokumen |
| **Karyawan 2 (Finance)** | `siti@hrms.local` / `EMP002` | `password` | Portal Karyawan Departemen Finance |
| **Karyawan 3 (Operations)**| `andi@hrms.local` / `EMP003` | `password` | Portal Karyawan Operasional (Shift Pagi, Tukar Shift, Pinjaman Aktif & Catatan SP 1) |
| **Karyawan 4 (Marketing)** | `dewi@hrms.local` / `EMP004` | `password` | Portal Karyawan dengan Status Resignasi Disetujui & Surat Paklaring Terbit |

---

## 📄 Lisensi
Hak Cipta &copy; 2026 PT Maju Nusantara. Premier Global Enterprise Edition.
