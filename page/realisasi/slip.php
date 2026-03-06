<?php
$id = $_GET['id'];
$ttgl1 = date("Y-m-d");
?>

<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-primary">
            <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
                <h3 class="box-title">Data Karyawan</h3>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="row" style=" background-color:white; border:1px ; color:black; ">
                        <div class="form-group col-md-2">
                            <label class="font-weight-bold"> Dari Tanggal</label>
                            <input placeholder="*" autocomplete="off" type="date" name="ttgl1" required value="<?php echo $ttgl1; ?>" class="form-control" />
                            <input type="submit" name="simpan" value="Search" class="btn btn-primary" style="margin-top:5px;">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-bold">Sampai Tanggal</label>
                            <input placeholder="*" autocomplete="off" type="date" name="ttgl2" value="<?php echo $ttgl1; ?>" required class="form-control" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$ttgl11 = @$_POST['ttgl1'];
$ttgl22 = @$_POST['ttgl2'];
$simpan = @$_POST['simpan'];

if ($simpan) {
    if (!function_exists('rupiah')) {
        function rupiah($angka) {
            return "Rp " . number_format($angka, 0, ',', '.');
        }
    }

    // Query join lengkap (sama seperti di root slip.php)
    $sql = "SELECT
        r.tgl_realisasi_detail,
        r.r_upah,
        r.ra_masuk,
        r.ra_keluar,
        r.r_potongan_telat,
        r.r_potongan_istirahat,
        r.r_potongan_lainnya,
        r.lembur,
        j.jabatan,
        d.nama_departmen,
        k.nama_karyawan
    FROM tb_realisasi_detail r
    JOIN ms_karyawan k ON r.id_karyawan = k.id_karyawan
    JOIN ms_jabatan j ON k.id_jabatan = j.id_jabatan
    JOIN ms_departmen d ON k.id_departmen = d.id_departmen
    WHERE r.id_karyawan = '$id'
      AND r.tgl_realisasi_detail BETWEEN '$ttgl11' AND '$ttgl22'
    ORDER BY r.tgl_realisasi_detail ASC";

    // Note: I noticed tgl_realisasi_detail was used in WHERE but I used an alias in SELECT. 
    // Let me check the column name again from previous grep or common naming.
    // In slip.php root it was r.tgl_realisasi_detail.

    $result = $koneksi->query($sql);
    
    // Attempt to get employee name for the heading even if results are empty
    $q_karyawan = $koneksi->query("SELECT nama_karyawan FROM ms_karyawan WHERE id_karyawan = '$id'");
    $d_karyawan = $q_karyawan->fetch_assoc();
    $namaKaryawan = $d_karyawan ? $d_karyawan['nama_karyawan'] : 'Karyawan';

    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Preview Slip Gaji: <b><?php echo $namaKaryawan; ?></b>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <div class="pull-right">
                            <a target="_blank" href="slip.php?id=<?php echo $id; ?>&ttgl1=<?php echo $ttgl11; ?>&ttgl2=<?php echo $ttgl22; ?>" class="btn btn-success btn-xs">
                                <i class="fa fa-download"></i> Download Excel
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="slipTable">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Upah</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Pot Telat</th>
                                    <th>Pot Istirahat</th>
                                    <th>Pot Lainnya</th>
                                    <th>Lembur</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $total = ($row['r_upah'] + $row['lembur']) - ($row['r_potongan_telat'] + $row['r_potongan_istirahat'] + $row['r_potongan_lainnya']);
                                        ?>
                                        <tr>
                                            <td><?php echo $row['tgl_realisasi_detail']; ?></td>
                                            <td><?php echo rupiah($row['r_upah']); ?></td>
                                            <td><?php echo $row['ra_masuk']; ?></td>
                                            <td><?php echo $row['ra_keluar']; ?></td>
                                            <td class="text-danger"><?php echo rupiah($row['r_potongan_telat']); ?></td>
                                            <td class="text-danger"><?php echo rupiah($row['r_potongan_istirahat']); ?></td>
                                            <td class="text-danger"><?php echo rupiah($row['r_potongan_lainnya']); ?></td>
                                            <td><?php echo rupiah($row['lembur']); ?></td>
                                            <td style="font-weight:bold;"><?php echo rupiah($total); ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data ditemukan untuk rentang tanggal tersebut.</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<script>
    $(document).ready(function() {
        if ($('#slipTable').length && $('#slipTable').find('tbody tr td[colspan]').length === 0) {
            $('#slipTable').DataTable({
                "searching": true,
                "paging": true,
                "info": true,
                "pageLength": 10
            });
        }
    });
</script>