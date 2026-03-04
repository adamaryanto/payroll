-- Migration: Pindahkan data Departemen & Sub Departemen ke tb_rkk_detail
-- Tanggal: 2026-03-04
-- Deskripsi: Menambahkan kolom id_departmen dan id_sub_department di tb_rkk_detail

-- 1. Tambah kolom id_departmen dan id_sub_department di tb_rkk_detail
ALTER TABLE `tb_rkk_detail` 
  ADD COLUMN `id_departmen` int(10) NOT NULL DEFAULT 0 AFTER `id_karyawan`,
  ADD COLUMN `id_sub_department` int(10) NOT NULL DEFAULT 0 AFTER `id_departmen`;

-- 2. Isi data existing dari ms_karyawan (backfill)
UPDATE `tb_rkk_detail` A 
  INNER JOIN `ms_karyawan` B ON A.id_karyawan = B.id_karyawan
SET A.id_departmen = IFNULL(B.id_departmen, 0), 
    A.id_sub_department = IFNULL(B.id_sub_department, 0);
