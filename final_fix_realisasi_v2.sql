SET sql_mode = '';
-- Fix tb_realisasi_detail (Missing PK and AI)
ALTER TABLE `tb_realisasi_detail` ADD PRIMARY KEY (`id_realisasi_detail`);
ALTER TABLE `tb_realisasi_detail` MODIFY `id_realisasi_detail` INT(10) NOT NULL AUTO_INCREMENT;

-- Fix tb_realisasi (Missing AI on column and required fields)
ALTER TABLE `tb_realisasi` MODIFY `id_realisasi` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tb_realisasi` MODIFY `keterangan` TEXT NULL;
ALTER TABLE `tb_realisasi` MODIFY `tgl_status` DATE DEFAULT NULL;

-- Ensure tgl_status has a default if NOT NULL (sometimes MODIFY is picky)
ALTER TABLE `tb_realisasi` ALTER COLUMN `tgl_status` SET DEFAULT NULL;
