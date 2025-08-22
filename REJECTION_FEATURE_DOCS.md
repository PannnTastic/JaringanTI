# Fitur Rejection untuk Sistem Permit

## Overview
Fitur rejection memungkinkan approver untuk menolak permit dengan alasan tertentu. Ketika permit ditolak, sistem akan:

1. Mengubah status permit menjadi "Ditolak"
2. Mencatat siapa yang menolak dan kapan
3. Menyimpan alasan penolakan 
4. Mengirim notifikasi ke pembuat permit
5. Mencatat aktivitas dalam log sistem

## Field Database yang Diperlukan

### Tabel `permits`
- `rejected_by` VARCHAR(255) NULL - Nama role yang menolak
- `rejected_at` TIMESTAMP NULL - Waktu penolakan

### Tabel `approvers`  
- `rejection_reason` TEXT NULL - Alasan penolakan dari approver

### Tabel `notifications`
- Sudah dibuat dengan `php artisan notifications:table`

## Cara Kerja Rejection

### 1. Proses Penolakan
- Hanya approver yang sedang dalam giliran yang bisa menolak
- Form rejection mengharuskan approver mengisi alasan penolakan
- Status permit langsung berubah menjadi -1 (ditolak)
- Sistem mencatat semua detail penolakan

### 2. Notifikasi
- Pembuat permit mendapat notifikasi database
- Log aktivitas tercatat untuk audit trail
- Notifikasi real-time muncul di interface

### 3. Tampilan di Tabel
- Kolom "Detail Penolakan" muncul untuk permit yang ditolak
- Menampilkan: siapa yang menolak, kapan, dan alasannya
- Badge warna merah untuk status ditolak

## Fitur Tambahan

### Reset Approval (Admin Only)
- Admin dapat mereset permit yang sudah diproses
- Semua status approval kembali ke 0
- Semua approver mendapat notifikasi reset
- Permit bisa diproses ulang dari awal

### Logging Comprehensive  
- Setiap approve/reject tercatat dalam log
- Include: permit_id, user, role, waktu, alasan
- Berguna untuk audit dan troubleshooting

### Validation & Security
- Hanya approver dalam giliran yang bisa approve/reject
- Menggunakan database transaction untuk konsistensi data
- Error handling dengan rollback jika ada masalah

## Files yang Dimodifikasi

### Models
- `app/Models/Permit.php` - Tambah casting dan withPivot rejection_reason

### Resources
- `app/Filament/Resources/PermitResource.php` - Semua logika approval/rejection

### Services (Baru)
- `app/Services/PermitNotificationService.php` - Handle semua notifikasi

### Database
- Tambahkan field dengan SQL di `database_additions.sql`

## Cara Testing

1. Jalankan SQL di `database_additions.sql`
2. Login sebagai approver (Staff IT Network, Asisten Manajer, dll)
3. Buat permit baru atau lihat permit pending
4. Klik tombol "Tolak" pada permit yang bisa Anda proses
5. Isi alasan penolakan dan konfirmasi
6. Cek notifikasi dan log untuk memastikan berjalan

## Status Values

- `0` = Pending (menunggu approval)
- `1` = Approved (disetujui) 
- `-1` = Rejected (ditolak)

## Error Handling

- Database transaction untuk konsistensi
- Log error untuk debugging
- User notification untuk feedback
- Graceful fallback jika ada masalah

## Admin Features

- Lihat semua permit tanpa filter
- Reset approval untuk permit yang sudah diproses
- Access ke semua log dan audit trail
