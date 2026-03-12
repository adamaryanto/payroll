<?php
if (isset($_GET['id'])) {
    $idrealisasi = $_GET['id'];

    $tampildetail = $koneksi->query("SELECT * FROM tb_realisasi WHERE id_realisasi = '$idrealisasi'");
    $datadetail = $tampildetail->fetch_assoc();

    if (!$datadetail) {
        echo "<script>alert('Data Realisasi tidak ditemukan!'); window.location.href='?page=realisasi';</script>";
        exit;
    }

    $datatglrealisasi    = $datadetail['tgl_realisasi'];
    $dataketerangan      = $datadetail['keterangan'];
    $datadetailrealisasi = $datadetail['detail_realisasi'];
    $datajamkerja        = $datadetail['jam_kerja'];
    $datastatusrealisasi = $datadetail['status_realisasi'];
    $idrkk               = $datadetail['id_rkk'];

    $tampil = $koneksi->query("SELECT 
        A.*, 
        A.r_upah as upahkaryawan, 
        B.no_absen, 
        BB.nama_sub_department, 
        B.nama_karyawan, 
        D.nama_departmen, 
        C.tgl_realisasi, 
        B.OS_DHK,
        B.golongan,
        RD.status_rkk,
        RD.upah as upah_rkk,
        J.jam_masuk as shift_masuk,
        J.istirahat_masuk as shift_istirahat_masuk,
        (SELECT K3.nama_karyawan 
         FROM tb_rkk_update U1 
         JOIN tb_rkk_update U2 ON U1.id_rkk_detail = U2.id_rkk_detail 
         JOIN ms_karyawan K3 ON U2.id_karyawan = K3.id_karyawan 
         JOIN tb_rkk_detail RD2 ON U1.id_rkk_detail = RD2.id_rkk_detail
         WHERE U1.id_karyawan = A.id_karyawan 
         AND U1.status = 'Pengganti' 
         AND U2.status = 'Digantikan' 
         AND RD2.id_rkk = A.id_rkk
         LIMIT 1) as menggantikan,
        (SELECT K4.nama_karyawan 
         FROM tb_rkk_update U3 
         JOIN tb_rkk_update U4 ON U3.id_rkk_detail = U4.id_rkk_detail 
         JOIN ms_karyawan K4 ON U4.id_karyawan = K4.id_karyawan 
         WHERE U3.id_rkk_detail = A.id_rkk_detail 
         AND U3.status = 'Digantikan' 
         AND U4.status = 'Pengganti' 
         LIMIT 1) as digantikan_oleh
        FROM tb_realisasi_detail A 
        LEFT JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan
        LEFT JOIN tb_realisasi C ON A.id_realisasi = C.id_realisasi
        LEFT JOIN ms_departmen D ON B.id_departmen = D.id_departmen
        LEFT JOIN ms_sub_department BB ON B.id_sub_department = BB.id_sub_department
        LEFT JOIN tb_rkk_detail RD ON A.id_rkk_detail = RD.id_rkk_detail
        LEFT JOIN tb_jadwal J ON A.id_jadwal = J.id_jadwal
        WHERE A.id_realisasi = '$idrealisasi'
    ");

    $tampilrkk = $koneksi->query("SELECT * FROM tb_rkk WHERE id_rkk = '$idrkk'");
    $datarkk = $tampilrkk->fetch_assoc();

    if (!$datarkk) {
        $datatglrkk = "";
        $dataketeranganrkk = "";
        $datajamkerjarkk = "";
    } else {
        $datatglrkk = $datarkk['tgl_rkk'];
        $dataketeranganrkk = $datarkk['keterangan'];
        $datajamkerjarkk = $datarkk['jam_kerja'];
    }

    // Ambil Data Denda Global
    $queryDenda = $koneksi->query("SELECT * FROM tb_denda LIMIT 1");
    $dataDenda = $queryDenda->fetch_assoc();
    $globalDendaMasuk = $dataDenda['denda_masuk'] ?? 0;
    $globalDendaIstirahat = $dataDenda['denda_istirahat'] ?? 0;
    $globalDendaPulang = $dataDenda['denda_pulang'] ?? 0;
} else {
    $datatglrealisasi    = "";
    $dataketerangan      = "";
    $datadetailrealisasi = "";
    $datajamkerja        = "";
    $datastatusrealisasi = 'pending';
}

if ($datastatusrealisasi == 'approve') {
    if ($_SESSION['role'] != "owner") {
        $status = "hidden";
    } else {
        $status = "";
    }
} else {
    $status = "";
}

$simpan = @$_POST['simpan'];
if ($simpan) {
    $tketerangan = @$_POST['tketerangan'];
    $sql = $koneksi->query("UPDATE tb_realisasi SET keterangan = '$tketerangan' WHERE id_realisasi = '$idrealisasi'");
    if ($sql) {
        echo '<script type="text/javascript">
                    alert("Data Tersimpan");
                    window.location.href="?page=realisasi&aksi=kelola&id=' . $idrealisasi . '";
                  </script>';
    }
}

$cleanup = @$_POST['cleanup'];
if ($cleanup) {
    // Run synchronization logic
    include 'fix_realisasi.php';
    $count = syncRealisasiData($koneksi, $idrealisasi);
    echo '<script type="text/javascript">
                alert("Berhasil menarik ' . $count . ' data dari record mesin.");
                window.location.href="?page=realisasi&aksi=kelola&id=' . $idrealisasi . '";
              </script>';
}

if (!function_exists('rupiah')) {
    function rupiah($angka)
    {
        if ($angka === null || $angka === "") $angka = 0;
        return "Rp " . number_format($angka, 0, ',', '.');
    }
}
?>

<div class="container-fluid" style="padding: 15px 0;">
    <div class="row">
        <div class="col-md-12">
            <div class="card-clean">
                <div class="border-b border-gray-200 py-4 px-4 md:px-5 bg-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h3 class="text-xl font-bold text-blue-600 m-0">
                        <i class="fas fa-list-alt mr-2"></i> Daftar Realisasi Upah
                    </h3>

                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                        <a href="?page=realisasi" class="inline-flex items-center bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 text-[14px] md:text-base font-medium py-2 px-4 rounded shadow-sm transition-colors w-full md:w-auto justify-center">
                            <i class="fas fa-arrow-left mr-1.5"></i> Kembali
                        </a>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data" class="bg-white">
                    <div class="p-4 md:p-5 bg-gray-50 border-b border-gray-100">
                        <div class="mb-6">
                            <div class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3 border-l-4 border-blue-600 pl-2">Rencana Upah</div>
                            <div class="row">
                                <div class="col-md-3 mb-3 md:mb-0">
                                    <label class="font-bold text-gray-700 text-sm">Tanggal</label>
                                    <input readonly type="date" value="<?php echo $datatglrkk; ?>" class="form-control text-base py-2" />
                                </div>
                                <div class="col-md-6 mb-3 md:mb-0">
                                    <label class="font-bold text-gray-700 text-sm">Keterangan</label>
                                    <input readonly type="text" value="<?php echo $dataketeranganrkk; ?>" class="form-control text-base py-2" />
                                </div>
                                <div class="col-md-3">
                                    <label class="font-bold text-gray-700 text-sm">Jam Kerja</label>
                                    <input readonly type="text" value="<?php echo $datajamkerjarkk; ?> Jam" class="form-control text-base py-2" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-bold text-emerald-600 uppercase tracking-wider mb-3 border-l-4 border-emerald-600 pl-2">Realisasi Upah</div>
                            <div class="row">
                                <div class="col-md-3 mb-3 md:mb-0">
                                    <label class="font-bold text-gray-700 text-sm">Tanggal</label>
                                    <input readonly type="date" name="ttgl1" value="<?php echo $datatglrealisasi; ?>" class="form-control text-base py-2" />
                                </div>
                                <div class="col-md-6 mb-3 md:mb-0">
                                    <label class="font-bold text-gray-700 text-sm">Keterangan</label>
                                    <input type="text" name="tketerangan" value="<?php echo $dataketerangan; ?>" placeholder="Masukkan keterangan..." class="form-control text-base py-2" autocomplete="off" />
                                </div>
                                <div class="col-md-3">
                                    <label class="font-bold text-gray-700 text-sm">Jam Kerja</label>
                                    <input readonly type="number" name="tjamkerja" value="<?php echo $datajamkerja; ?>" class="form-control text-base py-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 md:px-5 bg-white border-t border-gray-200" <?php echo $status; ?>>
                        <button type="submit" name="simpan" value="Simpan" class="btn btn-primary bg-blue-600 hover:bg-blue-700 border-none px-6 py-2">
                            <i class="fas fa-save mr-1.5"></i> Simpan Ket.
                        </button>
                        <button type="submit" name="cleanup" value="Cleanup" class="btn btn-warning bg-amber-500 hover:bg-amber-600 border-none px-6 py-2 ml-2 text-white" onclick="return confirm('Tarik data dari record mesin?')">
                            <i class="fas fa-sync-alt mr-1.5"></i> Tarik Data
                        </button>
                    </div>
                </form>

                <div class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3 ml-3 border-l-4 border-blue-600 pl-2"> <i class="fas fa-users mr-1.5"></i> List Karyawan</div>

                <div class="p-0">
                    <div class="table-responsive px-3 md:px-4 py-4">
                        <table class="table table-hover table-clean align-middle mb-0 table-modern" id="dataTables-example">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">No</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">No Absen</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Nama Karyawan</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Departemen</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Sub Bagian</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">OS/DHK</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Golongan</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Jam Masuk</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Jam Pulang</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Istirahat Keluar</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Istirahat Masuk</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Absen Masuk</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Absen Pulang</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Absen Istirahat Keluar</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Absen Istirahat Masuk</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Upah Pokok</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Lembur</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Pot. Telat</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Pot. Istirahat</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Pot. Pulang</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Pot. Lain</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-right">Upah Setelah Potongan</th>
                                    <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle">Hasil</th>
                                    <?php if (strtolower($_SESSION['role']) != 'admin hr') { ?>
                                        <th class="py-2 px-2 text-[12px] font-bold text-gray-700 uppercase align-middle text-center">Aksi</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $total = 0;
                                $jml_active = 0;

                                while ($data = $tampil->fetch_assoc()) {
                                    $upah = $data['upahkaryawan'];

                                    $isFullMissing = (empty($data['r_jam_masuk']) || $data['r_jam_masuk'] == '00:00:00') &&
                                        (empty($data['r_jam_keluar']) || $data['r_jam_keluar'] == '00:00:00');

                                    // Highlight if status is 'Tidak Hadir' or data is fully missing
                                    $rowClass = ($data['status_rkk'] == 'Tidak Hadir' || $isFullMissing) ? 'bg-red-custom' : '';
                                ?>
                                    <tr class="<?php echo $rowClass; ?>">
                                        <td data-label="No"><?php echo $no; ?></td>
                                        <td data-label="No Absen"><?php echo $data['no_absen']; ?></td>
                                        <td data-label="Nama Karyawan">
                                            <strong><?php echo $data['nama_karyawan']; ?></strong>
                                            <?php if (!empty($data['menggantikan'])) : ?>
                                                <div class="text-xs text-blue-600 font-bold italic">
                                                    <i class="fas fa-exchange-alt mr-1"></i> (Menggantikan <?php echo $data['menggantikan']; ?>)
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($data['digantikan_oleh'])) : ?>
                                                <div class="text-xs text-red-600 font-bold italic">
                                                    <i class="fas fa-user-times mr-1"></i> (Digantikan oleh <?php echo $data['digantikan_oleh']; ?>)
                                                </div>
                                            <?php endif; ?>

                                            <div class="mt-1">
                                                <?php if ($data['status_rkk'] == 'Hadir') : ?>
                                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Hadir</span>
                                                <?php elseif ($data['status_rkk'] == 'Tidak Hadir') : ?>
                                                    <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Tidak Hadir</span>
                                                <?php elseif ($data['status_rkk'] == 'Digantikan') : ?>
                                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Digantikan</span>
                                                <?php elseif ($data['status_rkk'] == 'Pengganti') : ?>
                                                    <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Pengganti</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td data-label="Departemen"><?php echo $data['nama_departmen']; ?></td>
                                        <td data-label="Sub Bagian"><?php echo $data['nama_sub_department']; ?></td>
                                        <td data-label="OS/DHK"><?php echo $data['OS_DHK']; ?></td>
                                        <td data-label="Golongan"><?php echo $data['golongan']; ?></td>
                                        <td data-label="Jam Masuk" class="<?php echo (empty($data['r_jam_masuk']) || $data['r_jam_masuk'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['r_jam_masuk']; ?></td>
                                        <td data-label="Jam Pulang" class="<?php echo (empty($data['r_jam_keluar']) || $data['r_jam_keluar'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['r_jam_keluar']; ?></td>
                                        <td data-label="Istirahat Keluar" class="<?php echo (empty($data['r_istirahat_keluar']) || $data['r_istirahat_keluar'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['r_istirahat_keluar']; ?></td>
                                        <td data-label="Istirahat Masuk" class="<?php echo (empty($data['r_istirahat_masuk']) || $data['r_istirahat_masuk'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['r_istirahat_masuk']; ?></td>
                                        <td data-label="Absen Masuk" class="<?php 
                                            $isLate = (!empty($data['ra_masuk']) && !empty($data['shift_masuk']) && strtotime($data['ra_masuk']) > strtotime($data['shift_masuk']));
                                            echo (empty($data['ra_masuk']) || $data['ra_masuk'] == '00:00:00' || $isLate) ? 'bg-red-custom' : ''; 
                                        ?>"><?php echo $data['ra_masuk']; ?></td>
                                        <td data-label="Absen Pulang" class="<?php 
                                            $isEarlyOut = (!empty($data['ra_keluar']) && $data['ra_keluar'] != '00:00:00' && !empty($data['r_jam_keluar']) && $data['r_jam_keluar'] != '00:00:00' && strtotime($data['ra_keluar']) < strtotime($data['r_jam_keluar']));
                                            echo (empty($data['ra_keluar']) || $data['ra_keluar'] == '00:00:00') ? 'bg-red-custom' : ($data['r_potongan_lainnya'] > 0 || $isEarlyOut ? 'bg-yellow-custom' : ''); 
                                        ?>"><?php echo $data['ra_keluar']; ?></td>
                                        <td data-label="Absen Istirahat Keluar" class="<?php echo (empty($data['ra_istirahat_keluar']) || $data['ra_istirahat_keluar'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['ra_istirahat_keluar']; ?></td>
                                        <td data-label="Absen Istirahat Masuk" class="<?php 
                                            $isLateBreak = (!empty($data['ra_istirahat_masuk']) && !empty($data['shift_istirahat_masuk']) && strtotime($data['ra_istirahat_masuk']) > strtotime($data['shift_istirahat_masuk']));
                                            echo (empty($data['ra_istirahat_masuk']) || $data['ra_istirahat_masuk'] == '00:00:00' || $isLateBreak) ? 'bg-red-custom' : ''; 
                                        ?>"><?php echo $data['ra_istirahat_masuk']; ?></td>

                                        <td data-label="Upah Pokok" class="text-right">
                                            <?php
                                            if (!empty($data['digantikan_oleh']) || $data['status_rkk'] == 'Tidak Hadir') {
                                                $data['upah_rkk'] = 0;
                                            }
                                            ?>
                                            <?= rupiah($data['upah_rkk']) ?>
                                        </td>
                                        <td data-label="Lembur" class="text-right">
                                            <?= rupiah($data['lembur']) ?>
                                        </td>
                                        <td data-label="Pot. Telat" class="text-right <?php echo ($isLate) ? 'bg-red-custom' : ''; ?>"><?= rupiah($isLate ? $globalDendaMasuk : 0) ?></td>
                                        <td data-label="Pot. Istirahat" class="text-right <?php echo ($isLateBreak) ? 'bg-red-custom' : ''; ?>"><?= rupiah($isLateBreak ? $globalDendaIstirahat : 0) ?></td>
                                        <td data-label="Pot. Pulang" class="text-right <?php echo ($isEarlyOut) ? 'bg-yellow-custom' : ''; ?>"><?= rupiah($isEarlyOut ? $globalDendaPulang : 0) ?></td>
                                        <td data-label="Pot. Lain" class="text-right <?php echo ($data['r_potongan_lainnya'] > 0) ? 'bg-yellow-custom' : ''; ?>"><?= rupiah($data['r_potongan_lainnya']) ?></td>

                                        <?php
                                        // Sesuaikan isi data potongan dengan denda global
                                        $potTelatValue = $isLate ? $globalDendaMasuk : 0;
                                        $potIstirahatValue = $isLateBreak ? $globalDendaIstirahat : 0;
                                        $potPulangValue = $isEarlyOut ? $globalDendaPulang : 0;

                                        if (!empty($data['digantikan_oleh']) || $data['status_rkk'] == 'Tidak Hadir') {
                                            $data['upah_rkk'] = 0;
                                            $data['lembur'] = 0;
                                            $potTelatValue = 0;
                                            $potIstirahatValue = 0;
                                            $potPulangValue = 0;
                                            $data['r_potongan_lainnya'] = 0;
                                        }
                                        $upah_setelah_potongan = $data['upah_rkk'] + $data['lembur'] - $potTelatValue - $potIstirahatValue - $potPulangValue - $data['r_potongan_lainnya'];
                                        ?>
                                        <?php if ($data['status_rkk'] != 'Digantikan') $jml_active++; ?>
                                        <td data-label="Upah Setelah Potongan" class="text-right font-black text-blue-700">
                                            <?= rupiah($upah_setelah_potongan) ?>
                                        </td>
                                        <?php $total += $upah_setelah_potongan; ?>

                                        <td data-label="Hasil"><?php echo $data['hasil_kerja']; ?></td>
                                        <?php if (strtolower($_SESSION['role']) != 'admin hr') { ?>
                                            <td data-label="Aksi">
                                                <div class="flex-action">
                                                    <?php if (strtolower($_SESSION['role']) != 'admin hr') { ?>
                                                        <a href="?page=realisasi&aksi=detail&id=<?php echo $data['id_realisasi_detail']; ?>"
                                                            class="btn btn-xs btn-info" style="background-color: #3498DB; border:none; border-radius:4px; padding:4px 10px;">
                                                            <i class="fa fa-eye"></i> Detail
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php
                                    $no++;
                                }
                                $jml_karyawan = $jml_active;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-6 hidden-xs"></div>
                    <div class="col-md-6 col-xs-12 text-right" style="text-align: left !important;">
                        <label class="font-weight-bold text-muted" style="font-size: 13px; margin-bottom:5px; display:block;">
                            Total Realisasi Upah (<?php echo $jml_karyawan; ?> Karyawan)
                        </label>
                        <input readonly type="text" value="<?php echo "Rp " . number_format($total, 0, ',', '.'); ?>" class="form-control total-box" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* 1. Reset wrapper agar tidak menggunakan float bawaan DataTables */
    .dataTables_wrapper {
        display: block !important;
    }

    /* 2. Memaksa area atas (Length & Filter) menjadi satu baris sejajar */
    .dataTables_wrapper::before,
    .dataTables_wrapper::after {
        display: none !important;
    }

    /* 3. Membuat container fleksibel untuk Length (kiri) dan Filter (kanan) */
    #dataTables-example_wrapper .row:first-child {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 20px !important;
        width: 100% !important;
    }

    /* 4. Styling Tampil _MENU_ (Kiri) */
    .dataTables_length {
        display: flex !important;
        align-items: center !important;
    }

    .dataTables_length label {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 0 !important;
    }

    .dataTables_length select {
        padding: 5px 10px !important;
        border: 1px solid #e0e6ed !important;
        border-radius: 8px !important;
    }

    /* 5. Styling Cari: (Kanan) */
    .dataTables_filter {
        text-align: right !important;
        display: flex !important;
        justify-content: flex-end !important;
    }

    .dataTables_filter label {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 0 !important;
    }

    .dataTables_filter input {
        padding: 6px 12px !important;
        border: 1px solid #e0e6ed !important;
        border-radius: 8px !important;
        width: 200px !important;
    }

    /* --- STYLING PAGINATE (PREV/NEXT) --- */
    .dataTables_wrapper .dataTables_paginate {
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        gap: 4px !important;
        padding-top: 15px !important;
    }

    .dataTables_paginate .paginate_button {
        border: 1px solid #e2e8f0 !important;
        background: white !important;
        border-radius: 6px !important;
        padding: 5px 12px !important;
        color: #475569 !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
    }

    .dataTables_paginate .paginate_button:hover {
        background: #f8fafc !important;
        color: #2563eb !important;
        border-color: #cbd5e1 !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: white !important;
    }

    .dataTables_paginate .paginate_button.disabled {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        cursor: not-allowed !important;
    }

    /* --- STYLING INFO --- */
    .dataTables_wrapper .dataTables_info {
        padding-top: 20px !important;
        color: #64748b !important;
        font-size: 13px !important;
    }

    .card-clean {
        background: #fff;
        border: 1px solid #E0E4E8;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card-header-clean {
        background-color: #2C3E50;
        color: #ffffff;
        padding: 12px 15px;
        font-weight: 600;
        font-size: 15px;
    }

    .section-title {
        color: #34495E;
        border-left: 4px solid #3498DB;
        padding-left: 8px;
        font-weight: bold;
        margin-bottom: 10px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-control-clean {
        border-radius: 4px;
        border: 1px solid #CCD1D1;
        padding: 6px 10px;
        background-color: #FAFAFA;
        width: 100%;
        font-size: 13px;
    }

    .form-control-clean:focus {
        border-color: #3498DB;
        box-shadow: none;
        background-color: #FFFFFF;
    }

    .total-box {
        background-color: #EAF2F8;
        border: 1px solid #AED6F1;
        color: #1A5276;
        height: 45px;
        font-size: 18px;
        font-weight: bold;
        text-align: right;
        border-radius: 6px;
        padding-right: 15px;
    }

    /* Styling Dasar Table */
    #dataTables-example {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100%;
    }

    #dataTables-example thead th {
        padding: 8px 4px !important;
        font-size: 11px !important;
    }

    .table-clean tbody td {
        vertical-align: middle;
        font-size: 11px;
        color: #000;
        font-weight: bold;
        border-top: 1px solid #E0E4E8;
        padding: 6px 4px !important;
    }

    .bg-red-custom {
        background-color: #e74c3c !important;
        color: #fff !important;
    }

    .bg-yellow-custom {
        background-color: #f1c40f !important;
        color: #000 !important;
    }

    /* Form Filter & Length Menu DataTables */
    .dataTables_wrapper .dataTables_length select {
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        padding: 2px 6px;
        margin: 0 5px;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px !important;
        border: 1px solid #e5e7eb !important;
        padding: 6px 10px !important;
        outline: none;
        transition: all 0.2s;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #3498DB !important;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    /* PAGINATION STYLING DataTables */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #e5e7eb !important;
        background: white !important;
        border-radius: 6px !important;
        padding: 4px 10px !important;
        color: #4b5563 !important;
        font-weight: 500 !important;
        font-size: 12px;
        cursor: pointer;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #3498DB !important;
        border-color: #3498DB !important;
        color: white !important;
    }

    /* =========================================
       RESPONSIVE TABLE FIT SCREEN (Mobile View) 
       ========================================= */
    @media screen and (max-width: 768px) {
        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .col-md-12 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .card-clean {
            border-radius: 0 !important;
            border-left: none !important;
            border-right: none !important;
            margin-bottom: 0 !important;
        }

        .table-responsive {
            border: none !important;
            overflow-x: visible !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        #dataTables-example_wrapper {
            padding: 0 10px;
        }

        #dataTables-example_wrapper .row:first-child {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 15px;
            margin-bottom: 15px !important;
            width: 100% !important;
        }

        .dataTables_filter,
        .dataTables_length {
            display: flex !important;
            width: 100% !important;
            justify-content: flex-start !important;
        }

        .dataTables_filter input {
            width: 100% !important;
            max-width: 100% !important;
        }

        .dataTables_paginate {
            justify-content: center !important;
            flex-wrap: wrap;
        }

        #dataTables-example {
            width: 100% !important;
            margin: 0 !important;
        }

        /* --- STYLING MODERN CARD KARYAWAN --- */
        .table-modern tbody tr {
            display: block;
            margin: 0 5px 20px 5px;
            /* Jarak antar card */
            border: 1px solid #cbd5e1 !important;
            /* Border card */
            border-radius: 12px !important;
            /* Sudut melengkung halus */
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            /* Shadow elegan */
            overflow: hidden;
            padding: 0;
        }

        .table-modern thead {
            display: none !important;
        }

        .table-modern tbody td {
            display: flex;
            flex-direction: column;
            align-items: flex-start !important;
            text-align: left !important;
            /* Memaksa rata kiri */
            padding: 12px 16px !important;
            border: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            /* Garis tipis antar baris di dalam card */
            width: 100% !important;
            font-size: 14px;
            white-space: normal !important;
        }

        /* OVERRIDE CLASS TEXT-RIGHT AGAR TIDAK NUMPUK DI KANAN PADA MOBILE */
        .table-modern tbody td.text-right,
        .table-modern tbody td.text-center {
            text-align: left !important;
            align-items: flex-start !important;
        }

        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
            width: 100%;
        }

        .table-modern tbody td:last-child {
            border-bottom: none !important;
            background-color: #f8fafc;
            /* Highlight area tombol aksi */
            padding-top: 16px !important;
            padding-bottom: 16px !important;
        }

        .flex-action {
            display: flex;
            justify-content: flex-start;
            width: 100%;
            margin-top: 0;
        }

        .flex-action a {
            width: 100%;
            text-align: center;
            padding: 12px !important;
            font-size: 15px !important;
            border-radius: 8px;
        }

        .card-header-clean {
            font-size: 16px;
            padding: 15px;
        }

        .section-title {
            font-size: 15px;
            margin-top: 15px;
            padding-left: 15px;
        }

        .panel-body {
            padding: 15px 0 !important;
        }

        .panel-body .row,
        .panel-body .form-group {
            padding: 0 15px;
        }

        .panel-body hr {
            margin: 15px !important;
        }

        .form-control-clean {
            font-size: 15px;
            padding: 10px;
        }

        .form-group label {
            font-size: 14px;
        }

        .total-box {
            font-size: 16px;
            height: 45px;
            text-align: left;
            padding-left: 10px;
            margin-bottom: 15px;
        }

        .header-btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        .header-btn-group button,
        .header-btn-group a {
            width: 100%;
            text-align: center;
            padding: 12px !important;
            font-size: 15px;
        }
    }
</style>

<script>
    $(document).ready(function() {
        var isMobile = window.innerWidth <= 768;

        $('#dataTables-example').DataTable({
            pageLength: 25,
            autoWidth: false,
            responsive: false,
            scrollX: !isMobile,
            autoWidth: !isMobile,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            language: {
                search: "Cari:",
                searchPlaceholder: "Cari data...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            }
        });

        // Dihapus style .css('float') bawaan agar tidak bentrok dengan flexbox pada mobile
        $('.dataTables_filter').addClass('mb-3');
        $('.dataTables_length').addClass('mb-3');
    });
</script>   