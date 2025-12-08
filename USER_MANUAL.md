# User Manual - Kursus Ryan Komputer

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Panduan Pengguna Umum](#2-panduan-pengguna-umum)
3. [Panduan Student](#3-panduan-student)
4. [Panduan Instructor](#4-panduan-instructor)
5. [Panduan Admin](#5-panduan-admin)

---

## 1. Pendahuluan

### 1.1 Tentang Aplikasi

**Kursus Ryan Komputer** adalah platform manajemen kursus komputer yang menyediakan layanan pembelajaran online maupun offline. Platform ini memungkinkan pengguna untuk mendaftar kursus, mengakses materi pembelajaran, mengikuti ujian, dan mendapatkan sertifikat.

### 1.2 Role Pengguna

Aplikasi ini memiliki 3 role utama:

| Role | Deskripsi |
|------|-----------|
| **Admin** | Mengelola seluruh sistem termasuk kursus, pengguna, pembayaran, dan laporan |
| **Instructor** | Mengelola materi kursus, ujian, dan berinteraksi dengan siswa |
| **Student** | Mengikuti kursus, mengakses materi, mengerjakan ujian, dan mendapatkan sertifikat |

### 1.3 Persyaratan Sistem

- Browser modern (Chrome, Firefox, Safari, Edge)
- Koneksi internet stabil
- JavaScript diaktifkan

---

## 2. Panduan Pengguna Umum

### 2.1 Registrasi Akun

1. Kunjungi halaman utama website
2. Klik tombol **"Daftar"** atau **"Register"**
3. Isi formulir registrasi:
   - Nama lengkap
   - Email
   - Password
   - Konfirmasi password
4lik **"Daftar"**
5. Akun berhasil dibuat, silakan login

**Alternatif: Registrasi dengan Google**
1. Klik tombol **"Login dengan Google"**
2. Pilih akun Google Anda
3. Izinkan akses aplikasi
4. Akun otomatis terdaftar

### 2.2 Login

1. Kunjungi halaman login (`/login`)
2. Masukkan email dan password
3. Klik **"Login"**
4. Anda akan diarahkan ke dashboard sesuai role

**Login dengan Google:**
1. Klik **"Login dengan Google"**
2. Pilih akun Google yang sudah terdaftar

### 2.3 Melihat Daftar Kursus

1. Dari halaman utama, klik menu **"Daftar Kursus"**
2. Lihat semua kursus yang tersedia
3. Klik kursus untuk melihat detail:
   - Deskripsi kursus
   - Durasi (dalam bulan)
   - Harga
   - Jumlah peserta

### 2.4 Verifikasi Sertifikat

1. Kunjungi halaman **"Verifikasi Sertifikat"** (`/verifikasi-sertifikat`)
2. Masukkan nomor sertifikat
3. Klik **"Verifikasi"**
4. Sistem akan menampilkan informasi sertifikat jika valid

---

## 3. Panduan Student

### 3.1 Dashboard Student

Setelah login, Anda akan melihat dashboard dengan komponen:
- **Ringkasan** - Overview kursus yang diikuti
- **Lanjutkan Belajar** - Materi terakhir yang dipelajari
- **Penghitung Hari Aktif** - Jumlah hari belajar
- **Status Pembayaran** - Info pembayaran pending
- **Sertifikat Siap** - Sertifikat yang bisa didownload
- **Chat** - Shortcut ke fitur chat

### 3.2 Mendaftar Kursus

1. Dari halaman **Daftar Kursus**, pilih kursus yang diinginkan
2. Klik tombol **"Daftar Sekarang"** atau **"Tambah ke Keranjang"**
3. Buka halaman **Keranjang** (`/cart`)
4. Review kursus yang dipilih
5. Klik **"Checkout"**
6. Lengkapi data diri jika diminta
7. Submit pendaftaran

### 3.3 Upload Bukti Pembayaran

1. Masuk ke menu **"Pembayaran"** di sidebar
2. Cari pembayaran dengan status **"Pending"**
3. Klik **"Upload Bukti"**
4. Pilih file gambar bukti transfer
5. Klik **"Upload"**
6. Tunggu verifikasi dari admin

### 3.4 Mengakses Materi

1. Klik menu **"Materi"** di sidebar
2. Pilih kursus yang ingin dipelajari
3. Klik materi yang ingin dibaca
4. Setelah selesai, klik **"Tandai Selesai"** untuk menandai progress

### 3.5 Mengikuti Ujian

**Melihat Daftar Ujian:**
1. Klik menu **"Ujian"** di sidebar
2. Pilih kursus untuk melihat ujian yang tersedia

**Mengerjakan Ujian Final:**
1. Klik ujian final yang tersedia
2. Baca soal dengan teliti
3. Jawab semua pertanyaan
4. Klik **"Submit"**

**Mengerjakan Ujian Praktikum:**
1. Klik ujian praktikum
2. Baca instruksi tugas
3. Kerjakan tugas sesuai instruksi
4. Upload file hasil kerja
5. Klik **"Submit"**

**Melihat Hasil Ujian:**
1. Klik menu **"Ujian"** > **"Hasil Ujian"**
2. Lihat nilai dan feedback dari instructor

### 3.6 Download Sertifikat

1. Klik menu **"Sertifikat"** di sidebar
2. Jika sertifikat sudah tersedia, klik **"Download"**
3. Sertifikat akan terunduh dalam format PDF

> **Catatan:** Sertifikat hanya tersedia setelah menyelesaikan semua materi dan lulus ujian.

### 3.7 Chat

1. Klik menu **"Chat"** di sidebar
2. Klik **"Buat Percakapan Baru"** untuk memulai chat
3. Pilih admin atau instructor yang ingin dihubungi
4. Ketik pesan dan klik **"Kirim"**

### 3.8 Melihat Pengumuman

1. Klik menu **"Pengumuman"** di sidebar
2. Lihat daftar pengumuman terbaru
3. Klik pengumuman untuk membaca detail

---

## 4. Panduan Instructor

### 4.1 Dashboard Instructor

Dashboard menampilkan:
- **Kursus Saya** - Daftar kursus yang diampu
- **Pesan Terbaru** - Chat dari siswa
- **Ringkasan Siswa** - Jumlah siswa per kursus

### 4.2 Manajemen Materi

**Melihat Materi:**
1. Klik menu **"Materi"** di sidebar
2. Pilih kursus untuk melihat daftar materi

**Membuat Materi Baru:**
1. Dari halaman materi kursus, klik **"Tambah Materi"**
2. Isi formulir:
   - Judul materi
   - Konten/deskripsi
   - File attachment (opsional)
   - Urutan materi
3. Klik **"Simpan"**

**Mengedit Materi:**
1. Klik tombol **"Edit"** pada materi
2. Ubah informasi yang diperlukan
3. Klik **"Update"**

**Menghapus Materi:**
1. Klik tombol **"Hapus"** pada materi
2. Konfirmasi penghapusan

### 4.3 Manajemen Ujian

**Membuat Ujian Final:**
1. Klik menu **"Ujian"** di sidebar
2. Pilih kursus
3. Klik **"Buat Ujian Final"**
4. Isi formulir:
   - Judul ujian
   - Deskripsi/instruksi
   - Soal-soal ujian
   - Batas waktu (opsional)
5. Klik **"Simpan"**

**Membuat Ujian Praktikum:**
1. Dari halaman ujian kursus, klik **"Buat Praktikum"**
2. Isi formulir:
   - Judul praktikum
   - Instruksi tugas
   - Deadline pengumpulan
3. Klik **"Simpan"**

**Mengaktifkan/Menonaktifkan Ujian:**
1. Klik toggle **"Aktif"** pada ujian
2. Status ujian akan berubah

### 4.4 Penilaian Submission

1. Klik menu **"Ujian"** > **"Submissions"**
2. Pilih submission yang ingin dinilai
3. Review jawaban/file siswa
4. Masukkan nilai dan feedback
5. Klik **"Submit Nilai"**

### 4.5 Melihat Peserta Kursus

1. Klik menu **"Kursus"** di sidebar
2. Pilih kursus
3. Klik **"Lihat Peserta"**
4. Lihat daftar siswa beserta progress mereka

### 4.6 Generate Sertifikat

1. Dari halaman peserta kursus, cari siswa yang eligible
2. Klik **"Generate Sertifikat"**
3. Sertifikat akan dibuat untuk siswa tersebut

### 4.7 Membuat Pengumuman

1. Klik menu **"Pengumuman"** di sidebar
2. Klik **"Buat Pengumuman"**
3. Isi formulir:
   - Judul
   - Konten pengumuman
   - Target kursus (opsional)
4. Klik **"Publish"**

### 4.8 Chat dengan Siswa

1. Klik menu **"Pesan"** di sidebar
2. Lihat daftar percakapan aktif
3. Klik percakapan untuk membuka chat
4. Balas pesan dari siswa

---

## 5. Panduan Admin

### 5.1 Dashboard Admin

Dashboard menampilkan:
- **KPI Summary** - Total siswa, kursus, pendapatan
- **Grafik Pendapatan** - Trend pendapatan bulanan
- **Pendaftaran Terbaru** - Siswa baru mendaftar
- **Tiket Terbaru** - Chat/keluhan yang perlu ditangani

### 5.2 Manajemen Kursus

**Melihat Kursus:**
1. Klik menu **"Kursus"** di sidebar
2. Lihat daftar semua kursus

**Membuat Kursus Baru:**
1. Klik **"Tambah Kursus"**
2. Isi formulir:
   - Judul kursus
   - Slug (URL-friendly name)
   - Deskripsi
   - Gambar thumbnail
   - Durasi (bulan)
   - Harga
   - Instructor
3. Klik **"Simpan"**

**Mengedit Kursus:**
1. Klik **"Edit"** pada kursus
2. Ubah informasi yang diperlukan
3. Klik **"Update"**

**Menghapus Kursus:**
1. Klik **"Hapus"** pada kursus
2. Konfirmasi penghapusan

### 5.3 Manajemen Materi

1. Klik menu **"Materi"** di sidebar
2. Lihat overview semua materi
3. Pilih kursus untuk mengelola materi spesifik
4. Tambah, edit, atau hapus materi sesuai kebutuhan

### 5.4 Verifikasi Pembayaran

**Melihat Pembayaran Pending:**
1. Klik menu **"Pembayaran"** > **"Pending"**
2. Lihat daftar pembayaran yang menunggu verifikasi

**Memverifikasi Pembayaran:**
1. Klik pembayaran untuk melihat detail
2. Periksa bukti transfer yang diupload
3. Klik **"Verifikasi"** jika valid
4. Klik **"Tolak"** jika tidak valid

### 5.5 Manajemen Sertifikat

1. Klik menu **"Sertifikat"** di sidebar
2. Lihat semua sertifikat yang telah diterbitkan
3. **Approve** atau **Reject** sertifikat pending
4. Klik **"Download"** untuk mengunduh sertifikat

**Generate Sertifikat Manual:**
1. Dari halaman sertifikat, klik **"Generate"**
2. Pilih enrollment yang eligible
3. Sertifikat akan dibuat

### 5.6 Manajemen Pengguna

**Melihat Pengguna:**
1. Klik menu **"Pengguna"** di sidebar
2. Lihat daftar semua pengguna

**Membuat Pengguna Baru:**
1. Klik **"Tambah Pengguna"**
2. Isi formulir:
   - Nama
   - Email
   - Password
   - Role (admin/instructor/student)
3. Klik **"Simpan"**

**Mengedit Pengguna:**
1. Klik **"Edit"** pada pengguna
2. Ubah informasi yang diperlukan
3. Klik **"Update"**

**Mengubah Status Pengguna:**
1. Klik **"Status"** pada pengguna
2. Aktifkan atau nonaktifkan akun

### 5.7 Laporan Keuangan

1. Klik menu **"Keuangan"** di sidebar
2. Lihat laporan pendapatan
3. Filter berdasarkan periode
4. Klik **"Export PDF"** untuk mengunduh laporan
5. Klik **"Export Excel"** untuk mengunduh data

### 5.8 Analytics

**Overview Analytics:**
1. Klik menu **"Analytics"** di sidebar
2. Lihat statistik keseluruhan:
   - Total pendaftaran
   - Completion rate
   - Pendapatan

**Detail Kursus:**
1. Klik kursus untuk melihat analytics detail
2. Lihat:
   - Jumlah siswa
   - Progress rata-rata
   - Tingkat kelulusan

### 5.9 Hasil Ujian

1. Klik menu **"Hasil Ujian"** di sidebar
2. Pilih kursus untuk melihat hasil
3. Klik submission untuk melihat detail jawaban

### 5.10 Pengaturan Sistem

1. Klik menu **"Pengaturan"** di sidebar
2. Konfigurasi:
   - Informasi website
   - Kontak
   - Logo
   - Pengaturan lainnya
3. Klik **"Simpan"**

### 5.11 Pengumuman

1. Klik menu **"Pengumuman"** di sidebar
2. Kelola pengumuman:
   - Buat pengumuman baru
   - Edit pengumuman
   - Hapus pengumuman

### 5.12 Chat Management

1. Klik menu **"Chat"** di sidebar
2. Lihat semua percakapan
3. Klik **"Buat Chat"** untuk memulai percakapan baru
4. Balas pesan dari pengguna

---

## Bantuan & Kontak

Jika mengalami kendala, silakan:
1. Gunakan fitur **Chat** untuk menghubungi admin
2. Kunjungi halaman **Contact** di website
3. Hubungi admin melalui email yang tertera di website

---

*Dokumen ini dibuat untuk Kursus Ryan Komputer - Sistem Manajemen Kursus Komputer*
