-- Ensure primary keys exist and columns are auto-increment
ALTER TABLE `tb_realisasi` ADD PRIMARY KEY (`id_realisasi`);
ALTER TABLE `tb_realisasi` MODIFY `id_realisasi` INT(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tb_realisasi_detail` ADD PRIMARY KEY (`id_realisasi_detail`);
ALTER TABLE `tb_realisasi_detail` MODIFY `id_realisasi_detail` INT(10) NOT NULL AUTO_INCREMENT;
