<?php


$tampil = $koneksi->query("SELECT A.*, (select count(id_rkk_detail) from tb_rkk_detail where id_rkk = A.id_rkk ) as jml, (select sum(upah) from tb_rkk_detail where id_rkk = A.id_rkk ) as ttl from tb_rkk A");
if ($_SESSION['level'] != "OWNER") {
    $level =  "Hidden";
} else {
    $level = "";
}
if ($_SESSION['level'] == "OWNER") {
    $hr =  "Hidden";
} else {
    $hr = "";
}

?>

<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-primary">
            <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
                <h3 class="box-title">List Rencana Upah</h3>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="panel-body">



                    <div class="row" style=" background-color:white; border:1px ; color:black; ">

                        <div class="form-group col-md-6">
                            <a href="?page=rkk&aksi=tambah" class="btn btn-info">Tambah Data </a>
                        </div>

                    </div>


            </form>
            <div class="form-group "></div>

            <div class="table-responsive">
                <div class="row" style=" background-color:whitesmoke; border:1px ; color:black; ">




                </div>
                <table class="table table-bordered table-striped" id="dataTables-example">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Tanggal Input</th>
                            <th>Jam Kerja</th>
                            <th>Jumlah Karyawan</th>
                            <th>Total Upah Karyawan</th>
                            <th>Keterangan</th>


                            <th hidden="hidden">Action</th>
                            <th>Action</th>
                            <th>Print</th>
                            <th <?php echo $hr ?>>Action</th>
                            <th <?php echo $hr ?>>Action</th>

                            <th <?php
                                echo $level
                                ?>>Action</th>
                            <th <?php
                                echo $level
                                ?>>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;

                        while ($data = $tampil->fetch_assoc()) {
                            if ($data['status_rkk'] == "1") {
                                $a = "#FFEBCD";
                                $pro = "hidden";
                                $app = "";
                                $unpro = "";
                                $unapp = "hidden";
                            } elseif ($data['status_rkk'] == "2") {
                                $a = "#F0FFFF";
                                $pro = "hidden";
                                $app = "hidden";
                                $unpro = "hidden";
                                $unapp = "";
                            } elseif ($data['status_rkk'] == "0") {
                                $a = "transparent";
                                $app = "hidden";
                                $pro = "";
                                $unpro = "hidden";
                                $unapp = "hidden";
                            } elseif ($data['status_rkk'] == "3") {
                                $a = "#98FB98";
                                $pro = "hidden";
                                $app = "hidden";
                                $unpro = "hidden";
                                $unapp = "hidden";
                            } else {
                            }

                        ?>

                            <tr style=" background-color:<?php echo $a ?>; border:1px ; color:black; ">
                                <td><?php echo $no ?></td>
                                <td><?php echo $data['tgl_rkk'] ?></td>
                                <td><?php echo $data['detail_rkk'] ?></td>
                                <td><?php echo $data['jam_kerja'] ?></td>
                                <td><?php echo $data['jml'] ?></td>
                               <td><?php echo number_format($data['ttl'] ?? 0, 0, ',', '.') ?></td>
                                <td><?php echo $data['keterangan'] ?></td>

                                <td hidden="hidden">
                                    <a href="?page=rkk&aksi=hapus&id=<?php echo $data['id_rkk']; ?>" class="btn btn-danger"> Hapus</a>
                                </td>
                                <td>
                                    <a href="?page=rkk&aksi=kelola&id=<?php echo $data['id_rkk']; ?>" class="btn btn-warning"> Detail</a>
                                </td>
                                <td>
                                    <a href="excelrkk.php?id=<?php echo $data['id_rkk']; ?>" class="btn btn-info"> Cetak</a>
                                </td>
                                <td <?php echo $hr ?>>
                                    <div <?php echo $pro ?>>
                                        <a href="?page=rkk&aksi=accept&id=<?php echo $data['id_rkk']; ?>&iddetail=<?php echo "pro"; ?>"
                                            class="btn btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin Propose data ini?');">
                                            Propose
                                        </a>
                                    </div>
                                </td>
                                <td <?php echo $hr ?>>
                                    <div <?php echo $unpro ?>>
                                        <a href="?page=rkk&aksi=accept&id=<?php echo $data['id_rkk']; ?>&iddetail=<?php echo "unpro"; ?>"
                                            class="btn btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin UnPropose data ini?');">
                                            UnPropose
                                        </a>
                                    </div>
                                </td>

                                <td <?php
                                    echo $level
                                    ?>>
                                    <div <?php echo $app ?>> <a href="?page=rkk&aksi=accept&id=<?php echo $data['id_rkk']; ?>&iddetail=<?php echo "app"; ?>"
                                            class="btn btn-info"
                                            onclick="return confirm('Apakah Anda yakin ingin Approve data ini?');">
                                            Approve
                                        </a></div>
                                </td>
                                <td <?php
                                    echo $level
                                    ?>>

                                    <div <?php echo $unapp ?>> <a href="?page=rkk&aksi=accept&id=<?php echo $data['id_rkk']; ?>&iddetail=<?php echo "unapp"; ?>"
                                            class="btn btn-info"
                                            onclick="return confirm('Apakah Anda yakin ingin UnApprove data ini?');">
                                            Un-Approve
                                        </a></div>
                                </td>

                                <!-- <td>
                                <a  href="?page=order&id=<?php echo $data['id_transaksi']; ?>"  class="btn btn-success"> Update</a> </td> -->
                            </tr>

                        <?php $no++;
                        } ?>

                    </tbody>
                </table>
            </div>



        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            pageLength: 100,
            "searching": true
        });

    });
</script>

<?php

$ttgl1 = @$_POST['ttgl1'];
$ttgl2 = @$_POST['ttgl2'];

$simpan = @$_POST['simpan'];
$print = @$_POST['print'];
$excel = @$_POST['excel'];
if ($simpan) {
?><script type="text/javascript">
        window.location.href = "?page=cuti&ttgl1=<?php echo $ttgl1; ?>&ttgl2=<?php echo $ttgl2; ?>";
    </script>
<?php
}
if ($print) {
?><script type="text/javascript">
        window.location.href = "laporanpendapatan.php?ttgl1=<?php echo $ttgl1; ?>&ttgl2=<?php echo $ttgl2; ?>";
    </script>
<?php
}
if ($excel) {
?><script type="text/javascript">
        window.location.href = "excelpendapatan.php?ttgl1=<?php echo $ttgl1; ?>&ttgl2=<?php echo $ttgl2; ?>";
    </script>
<?php
}



?>