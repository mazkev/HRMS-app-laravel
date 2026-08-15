# 🏢 HR Management System (HRMS) - PT Maju (Tier-1 Global Enterprise Suite)

Aplikasi sistem informasi manajemen sumber daya manusia (**HRMS Tier-1 Global Enterprise Suite**) terlengkap dan modern berbasis **Laravel 11** untuk PT Maju Nusantara. 

Mencakup seluruh siklus operasional HR harian dan manajemen talenta tingkat lanjut: **Absensi Kamera Biometrik & GPS Geofencing**, **Rekrutmen & Pelacakan Pelamar (ATS)**, **Manajemen Aset & Inventaris Kantor**, **Klaim Biaya (Reimbursement)**, **Pinjaman Karyawan & Kasbon**, **Penilaian Kinerja (KPI Appraisal & Scorecard)**, **Pelatihan Karyawan (LMS Lite)**, **Penggajian & Cetak Slip Gaji (Payroll)**, **Audit Trail Log Keamanan**, serta **Progressive Web App (PWA)**.

Didesain dengan standar antarmuka **Clean Corporate SaaS (Gusto & Rippling Style)** yang lapang, elegan, kontras tinggi, dan bebas *AI slop*.

---

## 🌟 Modul & Fitur Komprehensif (Tier-1 Suite)

### 1. 📸 Absensi Kamera Live & GPS Geofencing
* **Live Biometric Viewfinder**: Viewport kamera webcam dengan pemandu posisi wajah.
* **GPS Radius Geofencing**: Validasi koordinat GPS ke titik kantor pusat via **Haversine formula** (Radius default: 250m).
* **Deteksi Keterlambatan Otomatis**: Toleransi keterlambatan dinamis menyesuaikan shift kerja staf.

### 2. 🏢 Rekrutmen & Pelacakan Pelamar (ATS & Onboarding)
* **Job Postings Manager**: Publikasi lowongan kerja per divisi unit kerja.
* **Visual Pipeline**: Pelacakan tahapan seleksi (*Applied* $\rightarrow$ *Screening* $\rightarrow$ *Interview* $\rightarrow$ *Offering* $\rightarrow$ *Hired*).
* **1-Click Convert to Employee**: Konversi otomatis kandidat yang diterima (*Hired*) menjadi akun Karyawan resmi (NIK, email, dan password default).

### 3. 💻 Manajemen Inventaris & Aset Kantor (Asset Tracker)
* **Pencatatan Hardware & Kendaraan**: Pendataan unit laptop (MacBook/ThinkPad), monitor, smartphone dinas, dan mobil operasional.
* **Serah Terima Digital**: Pelacakan pemegang aset, tanggal serah terima, dan pemantauan kondisi fisik barang (*Good / Maintenance / Damaged*).

### 4. 💸 Pinjaman Karyawan & Kasbon (Employee Loan)
* **Kalkulasi Cicilan Otomatis**: Pemilihan tenor (1 - 12 bulan) dengan simulasi potongan per bulan secara *real-time*.
* **Auto-Payroll Deduction**: Cicilan pinjaman otomatis didebet pada rincian slip gaji bulanan.

### 5. 🧾 Klaim Biaya Operasional (Reimbursement)
* **Lampiran Bukti Kuitansi**: Upload foto nota pengeluaran (transportasi dinas luar, medis, konsumsi, ATK).
* **Alur Review HR Finance**: Verifikasi kuitansi dan persetujuan pencairan dana.

### 6. 🏆 Penilaian Kinerja & KPI (Performance Appraisal)
* **Scoring Multi-Dimensi**: Evaluasi berkala (Q1, Q2, Q3, Q4, Annual) dengan pembobotan: KPI (50%), Absensi (30%), Teamwork (20%).
* **Kartu Rapor Kinerja**: Rapor evaluasi kinerja transparan (*Grade A, B, C, D*).

### 7. 🎓 Pelatihan & Sertifikasi Karyawan (LMS Lite)
* **Katalog Pelatihan Internal**: Publikasi jadwal workshop, modul pelatihan, dan instruktur.
* **Pendaftaran Mandiri (Self-Enrollment)**: Pendaftaran langsung oleh karyawan sesuai kuota kelas.

### 8. 💰 Modul Penggajian & Cetak Slip Gaji (Payroll)
* **Auto-Kalkulasi Gaji**: Gaji pokok + tunjangan - denda telat - cicilan pinjaman.
* **Dokumen Slip Gaji Resmi**: Cetak/Download PDF Slip Gaji lengkap dengan kop surat PT Maju Nusantara dan kolom tanda tangan.

### 9. 🛡️ Audit Trail Log & Keamanan
* **Pencatatan Aktivitas Sensitif**: Riwayat perubahan data penting dengan timestamp, IP Address, dan user pelaksana untuk kepatuhan audit.

### 10. 📢 Papan Pengumuman & Brankas Dokumen (Bulletin & Vault)
* **Pengumuman Resmi**: Siaran memo direksi (*Pin to Top*).
* **Brankas Dokumen Digital**: Penyimpanan aman salinan berkas KTP, NPWP, PKWT, dan sertifikat.

### 11. 📱 Progressive Web App (PWA) Support
* Dapat di-*install* langsung ke layar utama smartphone (Android/iOS) dengan dukungan offline caching.

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
| **Admin HRD** | `admin@hrms.local` / `HR001` | `password` | Dashboard Eksekutif, Absensi GPS, Approval Cuti/Lembur, ATS Rekrutmen, Manajemen Aset, Kasbon, KPI, Pelatihan LMS, Payroll, Audit Log |
| **Karyawan 1 (IT)** | `budi@hrms.local` / `EMP001` | `password` | Dashboard Personal, Absensi Kamera + GPS, Cuti, Lembur, Reimburse, Kasbon, Aset Pegangan, Rapor KPI, Slip Gaji, Pelatihan, Brankas Dokumen |
| **Karyawan 2 (Finance)** | `siti@hrms.local` / `EMP002` | `password` | Portal Karyawan Departemen Finance |
| **Karyawan 3 (Operations)**| `andi@hrms.local` / `EMP003` | `password` | Portal Karyawan Operasional (Shift Pagi & Pinjaman Aktif) |

---

## 📄 Lisensi
Hak Cipta &copy; 2026 PT Maju Nusantara. Tier-1 Global Enterprise Suite.
