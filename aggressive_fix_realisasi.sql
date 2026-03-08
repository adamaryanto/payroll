SET sql_mode = '';
-- Fix invalid dates in tb_realisasi
UPDATE `tb_realisasi` SET `tgl_status` = '2026-03-08' WHERE `tgl_status` = '0000-00-00' OR `tgl_status` LIKE '% %' OR `tgl_status` IS NULL;
UPDATE `tb_realisasi` SET `tgl_status` = LEFT(`tgl_status`, 10);

-- Drop PK if exists and recreate with AUTO_INCREMENT
ALTER TABLE `tb_realisasi` DROP PRIMARY KEY;
ALTER TABLE `tb_realisasi` ADD PRIMARY KEY (`id_realisasi`);
ALTER TABLE `tb_realisasi` MODIFY `id_realisasi` INT(10) NOT NULL AUTO_INCREMENT;

-- Same for detail if needed (checking if it also has issues)
ALTER TABLE `tb_realisasi_detail` DROP PRIMARY KEY;
ALTER TABLE `tb_realisasi_detail` ADD PRIMARY KEY (`id_realisasi_detail`);
ALTER TABLE `tb_realisasi_detail` MODIFY `id_realisasi_detail` INT(10) NOT NULL AUTO_INCREMENT;
