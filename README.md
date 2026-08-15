# 🏢 HR Management System (HRMS) - PT Maju (Tier-1 Global Enterprise Suite)

Aplikasi sistem informasi manajemen sumber daya manusia (**HRMS Tier-1 Global Enterprise Suite**) terlengkap dan modern berbasis **Laravel 11** untuk PT Maju Nusantara. 

Mencakup seluruh siklus operasional HR harian, analitik eksekutif, dan manajemen talenta tingkat lanjut: **Executive Analytics Dashboard (Chart.js)**, **Struktur Organisasi Interaktif (Org Chart)**, **Absensi Kamera Biometrik & GPS Geofencing**, **Rekrutmen & Pelacakan Pelamar (ATS)**, **Manajemen Aset & Inventaris Kantor**, **Klaim Biaya (Reimbursement)**, **Pinjaman Karyawan & Kasbon**, **Penilaian Kinerja (KPI Appraisal & Scorecard)**, **Pelatihan Karyawan (LMS Lite)**, **Penggajian & Cetak Slip Gaji (Payroll)**, **Audit Trail Log Keamanan**, serta **Progressive Web App (PWA)**.

Didesain dengan standar antarmuka **Clean Corporate SaaS (Gusto & Rippling Style)** yang lapang, elegan, kontras tinggi, dan bebas *AI slop*.

---

## 🌟 Modul & Fitur Komprehensif (Tier-1 Suite)

### 1. 📊 Executive HR Intelligence & Analytics (Chart.js)
* **Tren Absensi & Ketepatan Waktu**: Visualisasi data line chart persentase kehadiran tepat waktu vs terlambat dalam 6 bulan terakhir.
* **Distribusi Anggaran Payroll**: Donut chart alokasi pengeluaran gaji per departemen.
* **Komparasi Tenaga Kerja & Utilisasi Cuti**: Bar chart perbandingan jumlah staf aktif dan total hari cuti yang telah disetujui.

### 2. 🌳 Struktur Organisasi Interaktif (Interactive Org Chart)
* **Visual Hierarchy Tree**: Bagan hierarki pohon garis komando perusahaan (Dewan Direksi/CEO $\rightarrow$ HR Management $\rightarrow$ Divisi Operasional $\rightarrow$ Staf) lengkap dengan avatar dan badge posisi.

### 3. 📸 Absensi Kamera Live & GPS Geofencing
* **Live Biometric Viewfinder**: Viewport kamera webcam dengan pemandu posisi wajah.
* **GPS Radius Geofencing**: Validasi koordinat GPS ke titik kantor pusat via **Haversine formula** (Radius default: 250m).
* **Deteksi Keterlambatan Otomatis**: Toleransi keterlambatan dinamis menyesuaikan shift kerja staf.

### 4. 🏢 Rekrutmen & Pelacakan Pelamar (ATS & Onboarding)
* **Job Postings Manager**: Publikasi lowongan kerja per divisi unit kerja.
* **Visual Pipeline**: Pelacakan tahapan seleksi (*Applied* $\rightarrow$ *Screening* $\rightarrow$ *Interview* $\rightarrow$ *Offering* $\rightarrow$ *Hired*).
* **1-Click Convert to Employee**: Konversi otomatis kandidat yang diterima (*Hired*) menjadi akun Karyawan resmi (NIK, email, dan password default).

### 5. 💻 Manajemen Inventaris & Aset Kantor (Asset Tracker)
* **Pencatatan Hardware & Kendaraan**: Pendataan unit laptop (MacBook/ThinkPad), monitor, smartphone dinas, dan mobil operasional.
* **Serah Terima Digital**: Pelacakan pemegang aset, tanggal serah terima, dan pemantauan kondisi fisik barang (*Good / Maintenance / Damaged*).

### 6. 💸 Pinjaman Karyawan & Kasbon (Employee Loan)
* **Kalkulasi Cicilan Otomatis**: Pemilihan tenor (1 - 12 bulan) dengan simulasi potongan per bulan secara *real-time*.
* **Auto-Payroll Deduction**: Cicilan pinjaman otomatis didebet pada rincian slip gaji bulanan.

### 7. 🧾 Klaim Biaya Operasional (Reimbursement)
* **Lampiran Bukti Kuitansi**: Upload foto nota pengeluaran (transportasi dinas luar, medis, konsumsi, ATK).
* **Alur Review HR Finance**: Verifikasi kuitansi dan persetujuan pencairan dana.

### 8. 🏆 Penilaian Kinerja & KPI (Performance Appraisal)
* **Scoring Multi-Dimensi**: Evaluasi berkala (Q1, Q2, Q3, Q4, Annual) dengan pembobotan: KPI (50%), Absensi (30%), Teamwork (20%).
* **Kartu Rapor Kinerja**: Rapor evaluasi kinerja transparan (*Grade A, B, C, D*).

### 9. 🎓 Pelatihan & Sertifikasi Karyawan (LMS Lite)
* **Katalog Pelatihan Internal**: Publikasi jadwal workshop, modul pelatihan, dan instruktur.
* **Pendaftaran Mandiri (Self-Enrollment)**: Pendaftaran langsung oleh karyawan sesuai kuota kelas.

### 10. 💰 Modul Penggajian & Cetak Slip Gaji (Payroll)
* **Auto-Kalkulasi Gaji**: Gaji pokok + tunjangan - denda telat - cicilan pinjaman.
* **Dokumen Slip Gaji Resmi**: Cetak/Download PDF Slip Gaji lengkap dengan kop surat PT Maju Nusantara dan kolom tanda tangan.

### 11. 🛡️ Audit Trail Log & Keamanan
* **Pencatatan Aktivitas Sensitif**: Riwayat perubahan data penting dengan timestamp, IP Address, dan user pelaksana untuk kepatuhan audit.

### 12. 📢 Papan Pengumuman & Brankas Dokumen (Bulletin & Vault)
* **Pengumuman Resmi**: Siaran memo direksi (*Pin to Top*).
* **Brankas Dokumen Digital**: Penyimpanan aman salinan berkas KTP, NPWP, PKWT, dan sertifikat.

### 13. 📱 Progressive Web App (PWA) Support
* Dapat di-*install* langsung ke layar utama smartphone (Android/iOS) dengan dukungan offline caching.

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
| **Admin HRD** | `admin@hrms.local` / `HR001` | `password` | Dashboard Eksekutif, Analytics Chart.js, Org Chart, Absensi GPS, Approval Cuti/Lembur, ATS Rekrutmen, Manajemen Aset, Kasbon, KPI, Pelatihan LMS, Payroll, Audit Log |
| **Karyawan 1 (IT)** | `budi@hrms.local` / `EMP001` | `password` | Dashboard Personal, Org Chart, Absensi Kamera + GPS, Cuti, Lembur, Reimburse, Kasbon, Aset Pegangan, Rapor KPI, Slip Gaji, Pelatihan, Brankas Dokumen |
| **Karyawan 2 (Finance)** | `siti@hrms.local` / `EMP002` | `password` | Portal Karyawan Departemen Finance |
| **Karyawan 3 (Operations)**| `andi@hrms.local` / `EMP003` | `password` | Portal Karyawan Operasional (Shift Pagi & Pinjaman Aktif) |

---

## 📄 Lisensi
Hak Cipta &copy; 2026 PT Maju Nusantara. Tier-1 Global Enterprise Suite.
