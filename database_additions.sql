-- SQL untuk menambahkan field rejection ke database
-- Jalankan query ini secara manual di database Anda

-- Tambahkan field rejected_by dan rejected_at ke tabel permits
ALTER TABLE permits ADD COLUMN rejected_by VARCHAR(255) NULL AFTER permit_status;
ALTER TABLE permits ADD COLUMN rejected_at TIMESTAMP NULL AFTER rejected_by;

-- Tambahkan field rejection_reason ke tabel approvers  
ALTER TABLE approvers ADD COLUMN rejection_reason TEXT NULL AFTER approved_at;

-- Verifikasi field sudah ditambahkan
DESCRIBE permits;
DESCRIBE approvers;
