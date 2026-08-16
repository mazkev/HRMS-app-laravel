# 🏢 HR Management System (HRMS) - PT Maju (Indonesian Statutory Compliance Edition)

Aplikasi sistem informasi manajemen sumber daya manusia (**HRMS Enterprise**) terlengkap dan modern berbasis **Laravel 11** untuk PT Maju Nusantara. 

Mencakup seluruh siklus operasional HR harian, kepatuhan pajak & ketenagakerjaan Indonesia, analitik eksekutif, serta manajemen talenta tingkat lanjut:
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

## 🌟 Modul & Fitur Flagship

### 1. 🇮🇩 Pajak PPh 21 (TER 2024) & Kalkulator BPJS
* **Kalkulasi PPh 21 TER Otomatis**: Mendukung seluruh status PTKP (`TK/0` s/d `K/3`) sesuai PP 58/2023 & PMK 168/2023.
* **Iuran BPJS Resmi**: BPJS Kesehatan (1% Karyawan, 4% Perusahaan) & BPJS Ketenagakerjaan (JHT 2%, JP 1%).
* **Rincian Potongan Slip Gaji**: Transparansi pemotongan pajak dan BPJS pada slip gaji resmi karyawan.

### 2. ⚠️ Surat Peringatan (SP 1, SP 2, SP 3) & Kedisiplinan
* **Penerbitan Surat Sanksi Resmi**: Pemilihan tingkat sanksi (SP 1, SP 2, SP 3), jenis pelanggaran, dan nomor surat otomatis (`SP/2026/08/001`).
* **Masa Berlaku Otomatis**: SP aktif selama 6 bulan dan dilengkapi template surat resmi siap cetak / PDF berkop surat PT Maju Nusantara.

### 3. 🚪 Manajemen Resignasi & Surat Paklaring (Offboarding)
* **Formulir 1-Month Notice**: Pengajuan pengunduran diri minimal 30 hari sebelum hari kerja terakhir.
* **Exit Clearance & Paklaring**: Verifikasi serah terima tugas/aset dan pencetakan otomatis **Surat Pengalaman Kerja (Paklaring)** resmi bertanda tangan HR Manager.

### 4. 🏥 Cuti Khusus & Wajib Upload Surat Dokter (SKD)
* **Kategori Cuti Non-Tahunan**: Cuti Melahirkan (90 hari), Cuti Menikah (3 hari), Cuti Berduka tanpa memotong kuota cuti tahunan.
* **Unggah Dokumen SKD**: Form permohonan cuti sakit mewajibkan unggah bukti surat dokter (JPG/PNG/PDF).

### 5. 🤖 Asisten Virtual HR (AI Policy Helpdesk)
* **Floating Chatbot 24/7**: Terpasang di pojok kanan bawah aplikasi untuk menjawab pertanyaan seputar sisa cuti, jadwal gajian, aturan lembur, kasbon, dan kebijakan HR secara interaktif.

### 6. 📊 Executive HR Intelligence & Analytics (Chart.js)
* **Tren Absensi**: Line chart kehadiran tepat waktu vs terlambat 6 bulan terakhir.
* **Distribusi Payroll**: Donut chart proporsi alokasi anggaran gaji per divisi.
* **Komparasi Cuti & Staf**: Bar chart perbandingan utilisasi hari cuti antar divisi.

### 7. 🌳 Struktur Organisasi Interaktif (Org Chart)
* **Bagan Visual Hierarki**: Garis komando Dewan Direksi $\rightarrow$ Manajemen HR $\rightarrow$ Unit Operasional $\rightarrow$ Staf dengan kartu profil dan NIK.

### 8. 📸 Absensi Kamera Live & GPS Geofencing
* **Live Biometric Viewfinder**: Validasi lokasi GPS kantor via Haversine formula (Radius: 250m) dan pencegah manipulasi presensi.

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
| **Admin HRD** | `admin@hrms.local` / `HR001` | `password` | Dashboard Eksekutif, Analytics Chart.js, Org Chart, Penerbitan SP, Approval Resignasi & Paklaring, Absensi GPS, Approval Cuti & SKD, ATS Rekrutmen, Manajemen Aset, Kasbon, KPI, Pelatihan LMS, Payroll & PPh 21, Audit Log |
| **Karyawan 1 (IT)** | `budi@hrms.local` / `EMP001` | `password` | Dashboard Personal, AI Helpdesk Bot, Absensi Kamera + GPS, Pengajuan Cuti, SP Saya, Resignasi, Lembur, Reimburse, Kasbon, Aset Pegangan, Rapor KPI, Slip Gaji, Pelatihan, Brankas Dokumen |
| **Karyawan 2 (Finance)** | `siti@hrms.local` / `EMP002` | `password` | Portal Karyawan Departemen Finance |
| **Karyawan 3 (Operations)**| `andi@hrms.local` / `EMP003` | `password` | Portal Karyawan Operasional (Shift Pagi, Pinjaman Aktif & Catatan SP 1) |
| **Karyawan 4 (Marketing)** | `dewi@hrms.local` / `EMP004` | `password` | Portal Karyawan dengan Status Resignasi Disetujui & Surat Paklaring Terbit |

---

## 📄 Lisensi
Hak Cipta &copy; 2026 PT Maju Nusantara. Indonesian Statutory Compliance Edition.
