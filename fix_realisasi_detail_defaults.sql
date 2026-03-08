SET sql_mode = '';
ALTER TABLE `tb_realisasi_detail`
  MODIFY `r_potongan_telat` int(10) NOT NULL DEFAULT 0,
  MODIFY `r_potongan_istirahat` int(10) NOT NULL DEFAULT 0,
  MODIFY `r_potongan_lainnya` int(10) NOT NULL DEFAULT 0,
  MODIFY `r_status` int(10) NOT NULL DEFAULT 0,
  MODIFY `r_update` varchar(20) NOT NULL DEFAULT '',
  MODIFY `ra_masuk` varchar(20) NOT NULL DEFAULT '',
  MODIFY `ra_keluar` varchar(20) NOT NULL DEFAULT '',
  MODIFY `ra_istirahat_masuk` varchar(20) NOT NULL DEFAULT '',
  MODIFY `ra_istirahat_keluar` varchar(20) NOT NULL DEFAULT '',
  MODIFY `status_realisasi_detail` int(10) NOT NULL DEFAULT 0,
  MODIFY `hasil_kerja` varchar(255) NOT NULL DEFAULT '',
  MODIFY `lembur` int(10) NOT NULL DEFAULT 0;
