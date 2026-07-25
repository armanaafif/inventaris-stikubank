# CODEX.md

# Sistem Inventaris Universitas Stikubank

## Tujuan

Project ini merupakan Sistem Inventaris Universitas Stikubank berbasis Laravel.

Project sudah memiliki fitur:

* Authentication
* Dashboard
* CRUD Barang
* Monitoring Stock
* Approval Request
* Borrowing
* History Transaksi
* Role Admin
* Role Staff

Semua fitur tersebut SUDAH STABIL.

Codex tidak boleh merusaknya.

---

# Aturan Umum

Sebelum menulis kode:

1. Analisis struktur project.
2. Jelaskan file yang akan diubah.
3. Jelaskan alasan perubahan.
4. Jangan menulis kode sebelum analisis selesai.

---

# Larangan

JANGAN:

* menghapus fitur lama
* mengubah route yang sudah berjalan
* mengubah middleware
* mengubah login
* mengubah approval yang sudah berjalan
* mengubah dashboard tanpa alasan
* mengubah migration lama

Semua perubahan database HARUS menggunakan migration baru.

---

# Cara Coding

Gunakan:

* Laravel Best Practice
* SOLID
* Clean Controller
* Service Layer
* Relationship Eloquent
* Migration baru
* Validation Laravel

Jangan membuat query SQL mentah jika Eloquent sudah cukup.

---

# Struktur Baru Barang

Barang tidak lagi hanya memiliki satu stok.

Barang harus mendukung distribusi stok.

Contoh

Barang

Proyektor Epson

Total

7 Unit

Distribusi

Gudang A

2 Unit

Gedung B

1 Unit

Ruang Rapat

2 Unit

Dipinjam

2 Unit

Total dihitung otomatis.

---

# Multi Lokasi

Tambahkan tabel baru.

Misal

consumable_stocks

Field

* id
* consumable_id
* location
* quantity
* created_at
* updated_at

Satu barang dapat memiliki banyak lokasi.

Jangan menyimpan lokasi di tabel consumables.

---

# Barang Dipinjam

Barang yang dipinjam TIDAK disimpan sebagai lokasi.

Barang dipinjam dihitung dari tabel Borrowings.

Status Dipinjam harus berasal dari data peminjaman.

Jika user membuka Detail Barang

harus muncul

Dipinjam

2 Unit

Jika diklik

baru tampil detail peminjaman

* peminjam
* keperluan
* tanggal pinjam
* tanggal kembali
* status

Data tersebut berasal dari tabel borrowings.

Jangan membuat tabel baru untuk data peminjaman.

---

# Detail Barang

Halaman Detail Barang harus memiliki urutan berikut

Informasi Barang

* Nama
* Kode Barang
* Satuan
* Kondisi
* Status
* Minimum Stok
* Total Stok

Distribusi Lokasi

(collapsible)

Status Dipinjam

(collapsible)

Riwayat Transaksi

Dokumen Nota

---

# Kode Barang

Tambahkan kode barang.

Format

INV-000001

Kode

* unik
* otomatis
* tidak menggunakan id database
* tidak ditampilkan di Dashboard
* digunakan untuk identitas barang

---

# Minimum Stock

minimum_stock

tidak wajib.

Jika kosong

NULL

Monitoring stok hanya berlaku apabila minimum_stock memiliki nilai.

---

# Dokumen Nota

Setiap barang dapat memiliki satu dokumen.

Jenis file

* PDF
* JPG
* PNG

Tidak perlu upload foto barang.

Tampilkan tombol

"Lihat Nota"

pada halaman Detail Barang.

---

# UI

Perbaiki usability.

Gunakan style yang konsisten.

Primary

Biru

Success

Hijau

Danger

Merah

Warning

Kuning

Semua tombol memiliki ukuran yang sama.

Gunakan icon Font Awesome.

Pastikan seluruh halaman responsive.

---

# Responsive

Sidebar

* desktop tetap
* mobile menjadi drawer

Semua tabel

responsive

Gunakan overflow-x-auto bila diperlukan.

---

# Approval

Approval lama tidak boleh berubah.

Tambahan fitur harus tetap mengikuti approval.

Staff

Tambah Barang

↓

Approval

↓

Admin

↓

Barang dibuat.

Admin

langsung membuat barang.

---

# Migration

Jangan pernah mengedit migration lama.

Gunakan migration baru.

---

# Output yang Diinginkan

Sebelum coding

berikan:

1. daftar file yang akan diubah

2. migration baru

3. relasi database

4. dampak perubahan

Setelah disetujui

baru mulai coding.

---

# Cara Implementasi

Kerjakan bertahap.

Tahap 1

Database

Tahap 2

Backend

Tahap 3

Frontend

Tahap 4

Testing

Jangan mengerjakan seluruh project sekaligus.

---

# Format Jawaban

Setiap selesai mengerjakan fitur

berikan

* file yang berubah
* alasan perubahan
* FULL CODE

Jangan memberikan potongan kode.

Jangan mengubah file yang tidak berhubungan.

---

# Prinsip

Prioritas utama

Stabilitas project.

Lebih baik menambah fitur sedikit demi sedikit daripada merombak seluruh sistem.
