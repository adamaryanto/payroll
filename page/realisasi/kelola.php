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
                    <h3 class="text-xl font-bold text-indigo-600 m-0">
                        <i class="fas fa-list-alt mr-2"></i> Daftar Realisasi Upah
                    </h3>

                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                        <a href="?page=realisasi" class="inline-flex items-center bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 text-[14px] md:text-base font-medium py-2 px-4 rounded shadow-sm transition-colors w-full md:w-auto justify-center">
                            <i class="fas fa-arrow-left mr-1.5"></i> Kembali
                        </a>

                        <?php if (strtolower($_SESSION['role']) == "owner") : ?>
                            <?php if ($datastatusrealisasi != 'approve') : ?>
                                <a href="?page=realisasi&aksi=accept&id=<?= $idrealisasi; ?>"
                                    class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white text-[14px] md:text-base font-medium py-2 px-4 rounded shadow-sm transition-colors w-full md:w-auto justify-center"
                                    onclick="return confirm('Approve Realisasi ini?');">
                                    <i class="fas fa-check-circle mr-1.5"></i> Approve
                                </a>
                            <?php else : ?>
                                <a href="?page=realisasi&aksi=unapprove&id=<?= $idrealisasi; ?>&iddetail=unapp"
                                    class="inline-flex items-center bg-rose-600 hover:bg-rose-700 text-white text-[14px] md:text-base font-medium py-2 px-4 rounded shadow-sm transition-colors w-full md:w-auto justify-center"
                                    onclick="return confirm('Batalkan Approve Realisasi ini?');">
                                    <i class="fas fa-times-circle mr-1.5"></i> Un-Approve
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data" class="bg-white">
                    <div class="p-4 md:p-5 bg-gray-50 border-b border-gray-100">
                        <div class="mb-6">
                            <div class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 border-l-4 border-indigo-600 pl-2">Rencana Upah</div>
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
                        <button type="submit" name="simpan" value="Simpan" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 border-none px-6 py-2">
                            <i class="fas fa-save mr-1.5"></i> Simpan Ket.
                        </button>
                        <button type="submit" name="cleanup" value="Cleanup" class="btn btn-warning bg-amber-500 hover:bg-amber-600 border-none px-6 py-2 ml-2 text-white" onclick="return confirm('Tarik data dari record mesin?')">
                            <i class="fas fa-sync-alt mr-1.5"></i> Tarik Data
                        </button>
                    </div>
                </form>

                <div class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3 ml-3 border-l-4 border-indigo-600 pl-2"> <i class="fas fa-users mr-1.5"></i> List Karyawan</div>

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

                                    $rowClass = $isFullMissing ? 'bg-red-custom' : '';
                                ?>
                                    <tr>
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

                                            <div class="mt-1 flex flex-wrap gap-1">
                                                <?php if ($data['status_rkk'] == 'Hadir') : ?>
                                                    <span class="inline-flex items-center bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-100 shadow-sm">
                                                        <i class="fas fa-check-circle mr-1 text-[8px]"></i> Hadir
                                                    </span>
                                                <?php elseif ($data['status_rkk'] == 'Tidak Hadir') : ?>
                                                    <span class="inline-flex items-center bg-rose-50 text-rose-700 text-[10px] font-bold px-2 py-0.5 rounded border border-rose-100 shadow-sm">
                                                        <i class="fas fa-times-circle mr-1 text-[8px]"></i> Tidak Hadir
                                                    </span>
                                                <?php elseif ($data['status_rkk'] == 'Digantikan') : ?>
                                                    <span class="inline-flex items-center bg-amber-50 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-100 shadow-sm">
                                                        <i class="fas fa-user-minus mr-1 text-[8px]"></i> Digantikan
                                                    </span>
                                                <?php elseif ($data['status_rkk'] == 'Pengganti') : ?>
                                                    <span class="inline-flex items-center bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded border border-indigo-100 shadow-sm">
                                                        <i class="fas fa-user-plus mr-1 text-[8px]"></i> Pengganti
                                                    </span>
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

                                        <td data-label="Absen Masuk" class="<?php echo (empty($data['ra_masuk']) || $data['ra_masuk'] == '00:00:00' || $data['r_potongan_telat'] > 0) ? 'bg-red-custom' : ''; ?>"><?php echo $data['ra_masuk']; ?></td>
                                        <td data-label="Absen Pulang" class="<?php echo (empty($data['ra_keluar']) || $data['ra_keluar'] == '00:00:00') ? 'bg-red-custom' : ($data['r_potongan_lainnya'] > 0 ? 'bg-yellow-custom' : ''); ?>"><?php echo $data['ra_keluar']; ?></td>
                                        <td data-label="Absen Istirahat Keluar" class="<?php echo (empty($data['ra_istirahat_keluar']) || $data['ra_istirahat_keluar'] == '00:00:00') ? 'bg-red-custom' : ''; ?>"><?php echo $data['ra_istirahat_keluar']; ?></td>
                                        <td data-label="Absen Istirahat Masuk" class="<?php echo (empty($data['ra_istirahat_masuk']) || $data['ra_istirahat_masuk'] == '00:00:00' || $data['r_potongan_istirahat'] > 0) ? 'bg-red-custom' : ''; ?>"><?php echo $data['ra_istirahat_masuk']; ?></td>

                                        <td data-label="Upah Pokok" class="text-right">
                                            <?= rupiah($data['upah_rkk']) ?>
                                        </td>
                                        <td data-label="Lembur" class="text-right">
                                            <?= rupiah($data['lembur']) ?>
                                        </td>
                                        <td data-label="Pot. Telat" class="text-right <?php echo ($data['r_potongan_telat'] > 0) ? 'bg-red-custom' : ''; ?>"><?= rupiah($data['r_potongan_telat']) ?></td>
                                        <td data-label="Pot. Istirahat" class="text-right <?php echo ($data['r_potongan_istirahat'] > 0) ? 'bg-red-custom' : ''; ?>"><?= rupiah($data['r_potongan_istirahat']) ?></td>
                                        <td data-label="Pot. Lain" class="text-right <?php echo ($data['r_potongan_lainnya'] > 0) ? 'bg-yellow-custom' : ''; ?>"><?= rupiah($data['r_potongan_lainnya']) ?></td>

                                        <?php
                                        if (!empty($data['digantikan_oleh'])) {
                                            $data['upah_rkk'] = 0;
                                            $data['lembur'] = 0;
                                            $data['r_potongan_telat'] = 0;
                                            $data['r_potongan_istirahat'] = 0;
                                            $data['r_potongan_lainnya'] = 0;
                                        }
                                        $upah_setelah_potongan = $data['upah_rkk'] + $data['lembur'] - $data['r_potongan_telat'] - $data['r_potongan_istirahat'] - $data['r_potongan_lainnya'];
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
    .card-clean {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
        overflow: hidden;
    }

    /* Total Box modern style */
    .total-box {
        background-color: #f8fafc;
        border: 2px solid #e2e8f0;
        color: #1e3a8a;
        height: 52px;
        font-size: 20px;
        font-weight: 800;
        text-align: right;
        border-radius: 10px;
        padding: 0 16px;
        transition: all 0.2s;
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
    }

    .total-box:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Modern Table Desktop */
    .table-modern {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100%;
        margin: 0 !important;
        border: none !important;
    }

    .table-modern thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 14px 12px !important;
        border-bottom: 2px solid #e2e8f0 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background-color: #f1f5f9 !important;
    }

    .table-modern tbody td {
        padding: 12px 12px !important;
        font-size: 13px !important;
        color: #334155 !important;
        border-bottom: 1px solid #f1f5f9 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        vertical-align: middle !important;
    }

    .table-modern tbody td strong {
        color: #1e293b;
        font-weight: 600;
    }

    /* Custom Highlight Colors */
    .bg-red-custom {
        background-color: #fef2f2 !important;
        color: #991b1b !important;
        font-weight: 700 !important;
    }

    .bg-yellow-custom {
        background-color: #fffbeb !important;
        color: #92400e !important;
        font-weight: 700 !important;
    }

    /* DataTables Pagination & Filter Modernization */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 6px 12px !important;
        font-size: 14px !important;
        color: #475569 !important;
        outline: none !important;
        transition: all 0.2s;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        border-radius: 8px !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        color: #475569 !important;
        font-weight: 600 !important;
        margin: 0 2px !important;
        transition: all 0.2s;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #f8fafc !important;
        color: #3b82f6 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
    }

    /* ===========================
       MODERN MOBILE CARD STYLING 
       =========================== */
    @media screen and (max-width: 768px) {
        .card-clean {
            border-radius: 0 !important;
            border-left: none !important;
            border-right: none !important;
            box-shadow: none !important;
        }

        .table-responsive {
            padding: 8px !important;
        }

        .table-modern tbody tr {
            display: block !important;
            margin-bottom: 20px !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            background: #ffffff !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05) !important;
            padding: 4px !important;
            overflow: hidden !important;
        }

        .table-modern tbody td {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 10px 16px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            width: 100% !important;
            font-size: 14px !important;
        }

        .table-modern tbody td:before {
            content: attr(data-label) !important;
            font-weight: 800 !important;
            color: #64748b !important;
            text-transform: uppercase !important;
            font-size: 10px !important;
            letter-spacing: 0.05em !important;
            margin-bottom: 0 !important;
            width: 40% !important;
            text-align: left !important;
        }

        /* Styling area Row pertama (Nama & No Absen) */
        .table-modern tbody td[data-label="Nama Karyawan"] {
            background-color: #f8fafc !important;
            border-radius: 12px 12px 0 0 !important;
            padding: 16px !important;
        }

        .table-modern tbody td[data-label="Nama Karyawan"]:before {
            display: none !important;
        }

        .table-modern tbody td[data-label="Nama Karyawan"] strong {
            font-size: 16px !important;
            color: #1e3a8a !important;
        }

        /* Area Aksi di paling bawah */
        .table-modern tbody td:last-child {
            border-bottom: none !important;
            background-color: #f8fafc !important;
            padding: 16px !important;
            border-radius: 0 0 12px 12px !important;
        }

        .flex-action a {
            flex: 1 !important;
            justify-content: center !important;
            background-color: #3b82f6 !important;
            color: white !important;
            font-weight: 700 !important;
            padding: 10px !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4) !important;
        }

        .total-box {
            margin: 0 15px 15px 15px !important;
            width: calc(100% - 30px) !important;
        }
    }
</style>

<script>
    $(document).ready(function() {
        var isMobile = window.innerWidth <= 768;

        $('#dataTables-example').DataTable({
            pageLength: 10,
            responsive: false,
            stateSave: true,
            scrollX: !isMobile,
            autoWidth: !isMobile,
            language: {
                search: "",
                searchPlaceholder: "Cari data...",
                lengthMenu: "Tampil _MENU_",
                info: "Menampilkan _START_ sd _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            }
        });

        $('.dataTables_filter').addClass('mb-2');
    });
</script>