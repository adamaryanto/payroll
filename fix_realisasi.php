<?php
/**
 * Synchronize realization data with machine logs and calculate deductions based on tb_denda
 */
function syncRealisasiData($koneksi, $id_realisasi) {
    // 1. Get Global Denda Settings
    $queryDenda = $koneksi->query("SELECT * FROM tb_denda LIMIT 1");
    $dataDenda = $queryDenda->fetch_assoc();
    $dendaMasuk = $dataDenda['denda_masuk'] ?? 0;
    $dendaIstirahat = $dataDenda['denda_istirahat'] ?? 0;

    // 2. Get Realization Info (to get date)
    $queryRealisasi = $koneksi->query("SELECT tgl_realisasi FROM tb_realisasi WHERE id_realisasi = '$id_realisasi'");
    $dataRealisasi = $queryRealisasi->fetch_assoc();
    $tgl = $dataRealisasi['tgl_realisasi'];

    // 3. Get All Details for this Realization
    $queryDetails = $koneksi->query("SELECT A.*, B.no_absen, C.jam_masuk as shift_masuk, C.jam_keluar as shift_keluar, C.istirahat_masuk as shift_istirahat_masuk, RD.upah as upah_rkk, RD.status_rkk as current_rkk_status
                                    FROM tb_realisasi_detail A 
                                    LEFT JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan 
                                    LEFT JOIN tb_jadwal C ON A.id_jadwal = C.id_jadwal
                                    LEFT JOIN tb_rkk_detail RD ON A.id_rkk_detail = RD.id_rkk_detail
                                    WHERE A.id_realisasi = '$id_realisasi'");
    
    $count = 0;
    while ($detail = $queryDetails->fetch_assoc()) {
        $idDetail = $detail['id_realisasi_detail'];
        $idRkkDetail = $detail['id_rkk_detail'];
        $userid = trim($detail['no_absen'] ?? '');
        $originalWage = $detail['upah_rkk'];
        
        $jamMasuk = '00:00:00';
        $jamPulang = '00:00:00';
        $jamIstK = '00:00:00';
        $jamIstM = '00:00:00';

        // 4. Fetch Attendance Logs from tb_record (only if NIK exists)
        if (!empty($userid)) {
            $qMasuk = $koneksi->query("SELECT detail_waktu FROM tb_record WHERE userid = '$userid' AND tgl = '$tgl' AND status = 0 ORDER BY detail_waktu ASC LIMIT 1");
            $qPulang = $koneksi->query("SELECT detail_waktu FROM tb_record WHERE userid = '$userid' AND tgl = '$tgl' AND status = 1 ORDER BY detail_waktu DESC LIMIT 1");
            $qIstK = $koneksi->query("SELECT detail_waktu FROM tb_record WHERE userid = '$userid' AND tgl = '$tgl' AND status = 2 ORDER BY detail_waktu ASC LIMIT 1");
            $qIstM = $koneksi->query("SELECT detail_waktu FROM tb_record WHERE userid = '$userid' AND tgl = '$tgl' AND status = 3 ORDER BY detail_waktu ASC LIMIT 1");

            $resM = $qMasuk->fetch_assoc();
            $resP = $qPulang->fetch_assoc();
            $resIK = $qIstK->fetch_assoc();
            $resIM = $qIstM->fetch_assoc();

            if ($resM) $jamMasuk = date('H:i:s', strtotime($resM['detail_waktu']));
            if ($resP) $jamPulang = date('H:i:s', strtotime($resP['detail_waktu']));
            if ($resIK) $jamIstK = date('H:i:s', strtotime($resIK['detail_waktu']));
            if ($resIM) $jamIstM = date('H:i:s', strtotime($resIM['detail_waktu']));
        }

        // 5. Calculate Deductions and Determine Attendance Status
        $potTelat = 0;
        $potIstirahat = 0;
        $realizedWage = $originalWage;
        $newRkkStatus = ($detail['current_rkk_status'] == 'Pengganti') ? 'Pengganti' : (($detail['current_rkk_status'] == 'Digantikan') ? 'Digantikan' : 'Hadir');

        // Check if Absent (No entry AND No exit)
        if ($jamMasuk == '00:00:00' && $jamPulang == '00:00:00') {
            if ($newRkkStatus != 'Pengganti' && $newRkkStatus != 'Digantikan') {
                $newRkkStatus = 'Tidak Hadir';
            }
            $realizedWage = 0;
        } else {
            // Late deduction logic
            if ($jamMasuk != '00:00:00' && !empty($detail['shift_masuk']) && $detail['shift_masuk'] != '00:00:00') {
                if (strtotime($jamMasuk) > strtotime($detail['shift_masuk'])) {
                    $potTelat = $dendaMasuk;
                }
            }


            // Break deduction logic
            if ($jamIstM != '00:00:00' && !empty($detail['shift_istirahat_masuk'])) {
                if (strtotime($jamIstM) > strtotime($detail['shift_istirahat_masuk'])) {
                    $potIstirahat = $dendaIstirahat;
                }
            }
        }
        
        // Force 0 wage for 'Digantikan'
        if ($newRkkStatus == 'Digantikan') {
            $realizedWage = 0;
        }

        // 6. Update tb_realisasi_detail
        $koneksi->query("UPDATE tb_realisasi_detail SET 
            r_jam_masuk = '$jamMasuk',
            r_jam_keluar = '$jamPulang',
            r_istirahat_keluar = '$jamIstK',
            r_istirahat_masuk = '$jamIstM',
            ra_masuk = '$jamMasuk',
            ra_keluar = '$jamPulang',
            ra_istirahat_keluar = '$jamIstK',
            ra_istirahat_masuk = '$jamIstM',
            r_upah = '$realizedWage',
            status_realisasi_detail = 1,
            r_update = NOW()
            WHERE id_realisasi_detail = '$idDetail'");
        
        // 7. Update status_rkk in tb_rkk_detail
        $koneksi->query("UPDATE tb_rkk_detail SET status_rkk = '$newRkkStatus' WHERE id_rkk_detail = '$idRkkDetail'");
        
        $count++;
    }

    return $count;
}
