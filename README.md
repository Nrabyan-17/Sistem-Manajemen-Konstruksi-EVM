# EVM Dashboard — PT Bintang Gandari

Web dashboard untuk pengendalian proyek konstruksi menggunakan metode **Earned Value Management (EVM)**. Sistem ini dirancang sebagai alat bantu monitoring kinerja proyek dari sisi jadwal dan biaya secara real-time.

> **Status:** Dalam pengembangan, Bagian 1 (tanpa integrasi desktop)

---

## Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Role dan Akses](#role-dan-akses)
- [Struktur Modul](#struktur-modul)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Roadmap](#roadmap)
- [Lisensi](#lisensi)

---

## Tentang Proyek

Sistem ini digunakan untuk pengendalian beberapa proyek konstruksi secara bersamaan. Sebelumnya proses monitoring proyek dilakukan secara manual melalui Excel dan menyulitkan deteksi keterlambatan dan pembengkakan biaya.

Sistem ini menggantikan proses manual tersebut dengan dashboard terpusat yang:

- Menghitung semua indikator EVM secara otomatis (BCWS, BCWP, ACWP, CV, SV, CPI, SPI, EAC, dll)
- Menyajikan status proyek real-time dengan indikator warna (hijau / kuning / merah)
- Menghasilkan laporan kemajuan fisik dan rugi laba siap cetak
- Mendukung pengelolaan banyak proyek dalam satu platform

---

## Fitur Utama

### Manajemen RAB Multi Versi
Sistem mendukung tiga versi RAB sesuai praktik industri konstruksi:
- **RAB Kontrak** — versi awal berdasarkan kontrak
- **RAB MC0** — setelah pengukuran bersama di lapangan
- **RAB MC100** — setelah pekerjaan selesai

### Manajemen Time Schedule Multi Versi
Empat versi time schedule:
- TS Kontrak, TS MC0, TS MC100, dan TS Perpanjangan Waktu

### Perhitungan EVM Otomatis
Semua indikator dihitung otomatis setelah ada data baru yang di-approve:

| Indikator | Rumus |
|---|---|
| CV | BCWP − ACWP |
| SV | BCWP − BCWS |
| CPI | BCWP ÷ ACWP |
| SPI | BCWP ÷ BCWS |
| EAC | ACWP + ETC |
| VAC | BAC − EAC |

### Sistem Approval
Laporan mingguan dan addendum kontrak harus di-approve Koordinator sebelum masuk ke perhitungan EVM.

### Laporan Cetak
- Laporan Kemajuan Fisik Mingguan (PDF)
- Time Schedule terkini (PDF)
- Laporan Rugi Laba Proyek (PDF)

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 11 (PHP 8.2+) |
| Frontend | Blade + Livewire + Alpine.js |
| Database | MySQL 8 |
| PDF Generation | Dompdf |
| Charts | Chart.js |
| Excel Import | PhpSpreadsheet |
| Styling | Tailwind CSS |

---

## Role dan Akses

| Role | Akses Utama |
|---|---|
| Super Admin | Kelola user dan konfigurasi sistem |
| Admin Proyek | IUV RAB, Time Schedule, Cetak Laporan, Dashboard |
| Pelaksana Lapangan | IUV Laporan Mingguan |
| Koordinator Proyek | Approval, Sinkronisasi Keuangan, kelola akun Pimpinan, Dashboard |
| Pimpinan | Dashboard EVM (read-only) |

---

## Struktur Modul

```
1. IUV RAB              → Multi versi (Kontrak, MC0, MC100)
2. IUV Time Schedule    → Multi versi (4 versi)
3. Data Keuangan        → Input manual (Bagian 1), sinkronisasi (Bagian 2)
4. IUV Laporan Mingguan → Input progres oleh Pelaksana
5. Cetak Laporan        → Export PDF
6. Dashboard EVM        → Monitoring real-time
```

---

## Instalasi

> Pastikan PHP 8.2+, Composer, MySQL 8+, dan Node.js 18+ sudah terinstall.

```bash
# Clone repository
git clone https://github.com/<username>/evm-dashboard-bintang-gandari.git
cd evm-dashboard-bintang-gandari

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Build assets
npm run build

# Jalankan development server
php artisan serve
```

Akses sistem di `http://localhost:8000`

---

## Konfigurasi

Sesuaikan file `.env`:

```env
APP_NAME="EVM Dashboard"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=evm_dashboard
DB_USERNAME=root
DB_PASSWORD=
```

---

## Roadmap

### Bagian 1 — Sistem Inti (in progress)
- [x] Setup project Laravel + Livewire
- [ ] Autentikasi dan manajemen user multi-role
- [ ] Modul IUV RAB multi versi
- [ ] Modul IUV Time Schedule multi versi
- [ ] Modul Laporan Mingguan dengan approval flow
- [ ] Modul Data Keuangan (input manual)
- [ ] Modul Addendum Kontrak
- [ ] Dashboard EVM dan Kurva S
- [ ] Cetak laporan PDF

### Bagian 2 — Integrasi Desktop (planned)
- [ ] Sinkronisasi berkala dengan aplikasi desktop existing
- [ ] Mekanisme sync agent atau shared database
- [ ] Audit log sinkronisasi data

---

## Dokumentasi

Dokumen SRS lengkap tersedia di folder `docs/` setelah klien menyetujui kepemilikan.

---

## Lisensi

Proyek ini dikembangkan secara eksklusif untuk PT Bintang Gandari. Hak penggunaan komersial dan distribusi diatur dalam kontrak terpisah dengan klien.

---

## Kontak Developer

Untuk pertanyaan teknis atau permintaan fitur, silakan hubungi developer melalui Issues di repository ini.