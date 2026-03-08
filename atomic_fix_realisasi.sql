SET sql_mode = '';
-- Fix invalid dates
UPDATE `tb_realisasi` SET `tgl_status` = '2026-03-08' WHERE `tgl_status` = '0000-00-00' OR `tgl_status` LIKE '% %' OR `tgl_status` IS NULL;
UPDATE `tb_realisasi` SET `tgl_status` = LEFT(`tgl_status`, 10);

-- Atomic fix for tb_realisasi
ALTER TABLE `tb_realisasi` DROP PRIMARY KEY, ADD PRIMARY KEY (`id_realisasi`), MODIFY `id_realisasi` INT(10) NOT NULL AUTO_INCREMENT;

-- Atomic fix for tb_realisasi_detail
ALTER TABLE `tb_realisasi_detail` DROP PRIMARY KEY, ADD PRIMARY KEY (`id_realisasi_detail`), MODIFY `id_realisasi_detail` INT(10) NOT NULL AUTO_INCREMENT;
