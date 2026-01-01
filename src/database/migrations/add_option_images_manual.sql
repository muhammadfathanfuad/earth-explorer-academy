-- Migration untuk menambahkan kolom gambar pada opsi puzzle urutan
-- Jalankan SQL ini langsung di database jika migration tidak bisa dijalankan via artisan

ALTER TABLE `quizzes` 
ADD COLUMN `option_a_image` VARCHAR(255) NULL AFTER `option_a`,
ADD COLUMN `option_b_image` VARCHAR(255) NULL AFTER `option_b`,
ADD COLUMN `option_c_image` VARCHAR(255) NULL AFTER `option_c`,
ADD COLUMN `option_d_image` VARCHAR(255) NULL AFTER `option_d`;

