<?php

if(isset($_GET['tdepartmen'])) {
   $idd = $_GET['tdepartmen'];
  if($idd=='99'){
  $tiddepartmen = '99';
$tnamadepartmen = 'All';
  }
    else{
 
  $tampildepartmen = $koneksi->query("select * from ms_departmen where id_departmen = '$idd'");
$datadept=$tampildepartmen->fetch_assoc();
$tiddepartmen = $datadept['id_departmen'];
$tnamadepartmen = $datadept['nama_departmen'];
}
  }
  else{
    $tiddepartmen = '99';
$tnamadepartmen = 'All';
  }
  


if(isset($_GET['ttgl1']) || isset($_GET['ttgl2'])){
    $ttgl1 = $_GET['ttgl1'];
    $ttgl2 = $_GET['ttgl2'];

if ($tiddepartmen == '99'){


$tampil = $koneksi->query("SELECT 
    A.tgl,
    A.userid,

    '' AS masukkerja,
    '' AS pulangkerja,
    '' AS istirahatkeluar,
    '' AS istirahatmasuk,

    /* MASUK PERTAMA ANTARA JAM 06–10 */
    (SELECT TIME_FORMAT(detail_waktu, '%H:%i')
       FROM tb_record 
       WHERE userid = A.userid 
         AND tgl = A.tgl
         AND HOUR(detail_waktu) BETWEEN 6 AND 10
       ORDER BY detail_waktu ASC
       LIMIT 1) AS masukkonvert,

    /* ISTIRAHAT JAM 13 */
    (SELECT TIME_FORMAT(detail_waktu, '%H:%i')
       FROM tb_record 
       WHERE userid = A.userid 
         AND tgl = A.tgl
         AND HOUR(detail_waktu) = 13
       ORDER BY detail_waktu ASC
       LIMIT 1) AS istirahatkonvert,

    /* PULANG >= 17 */
    (SELECT TIME_FORMAT(detail_waktu, '%H:%i')
       FROM tb_record 
       WHERE userid = A.userid 
         AND tgl = A.tgl
         AND HOUR(detail_waktu) >= 17
       ORDER BY detail_waktu DESC
       LIMIT 1) AS pulangkonvert,

    '' AS masuklebihawal,
    '' AS terlambat,
    '' AS pulanglebihawal,
    '' AS lembur,

    0 AS dendamasuk,
    0 AS dendaistirahat,

    B.nama_karyawan,
    B.upah_harian,
    DPT.nama_departmen

FROM tb_record A
LEFT JOIN ms_karyawan B ON A.userid = B.no_absen
LEFT JOIN ms_departmen DPT ON DPT.id_departmen = B.id_departmen

WHERE A.tgl BETWEEN '$ttgl1' AND '$ttgl2'
  AND B.nama_karyawan <> ''

GROUP BY A.userid, A.tgl   -- pastikan hanya 1 baris per userid+tgl
ORDER BY DPT.nama_departmen, A.tgl ASC;


");

  
}else{
  echo "string";
  $tampil = $koneksi->query("SELECT 
    A.tgl,
    A.userid,

    /* MASUK ANTARA 06–10 */
    (SELECT TIME_FORMAT(detail_waktu, '%H:%i')
       FROM tb_record 
       WHERE userid = A.userid 
         AND tgl = A.tgl
         AND HOUR(detail_waktu) BETWEEN 6 AND 10
       ORDER BY detail_waktu ASC
       LIMIT 1
    ) AS masukkonvert,

    '' AS masuklebihawal,
    '' AS terlambat,

    /* ISTIRAHAT JAM 13 */
    (SELECT TIME_FORMAT(detail_waktu, '%H:%i')
       FROM tb_record 
       WHERE userid = A.userid
         AND tgl = A.tgl
         AND HOUR(detail_waktu) = 13
       ORDER BY detail_waktu ASC
       LIMIT 1
    ) AS istirahatkonvert,

    /* PULANG >= 17 */
    (SELECT TIME_FORMAT(detail_waktu, '%H:%i')
       FROM tb_record 
       WHERE userid = A.userid
         AND tgl = A.tgl
         AND HOUR(detail_waktu) >= 17
       ORDER BY detail_waktu DESC
       LIMIT 1
    ) AS pulangkonvert,

    '' AS pulanglebihawal,
    '' AS lembur,

    0 AS dendamasuk,
    0 AS dendaistirahat,

    B.nama_karyawan,
    B.upah_harian,

    (SELECT nama_departmen
        FROM ms_departmen 
        WHERE id_departmen = B.id_departmen
    ) AS namadepartmen

FROM tb_record A
LEFT JOIN ms_karyawan B 
       ON A.userid = B.no_absen

WHERE A.tgl BETWEEN '$ttgl1' AND '$ttgl2'
  AND B.nama_karyawan <> ''
  AND B.id_departmen = '$tiddepartmen'

/* PENTING: hanya 1 baris per hari per user */
GROUP BY A.userid, A.tgl

ORDER BY namadepartmen, A.tgl ASC;

 
 
");
}

}else{
     $ttgl1 = date('Y-m-d');
    $ttgl2 = date('Y-m-d');

$tampil = $koneksi->query("SELECT
    R.tgl,
    R.userid,

    -- JAM MASUK
    (SELECT TIME_FORMAT(detail_waktu, '%H:%i')
     FROM tb_record 
     WHERE tgl = R.tgl 
       AND userid = R.userid
       AND (
            detail_waktu LIKE '%06:%' OR 
            detail_waktu LIKE '%07:%' OR
            detail_waktu LIKE '%08:%' OR
            detail_waktu LIKE '%09:%' OR
            detail_waktu LIKE '%10:%'
           )
     ORDER BY detail_waktu ASC
     LIMIT 1
    ) AS masukkonvert,

    -- JAM ISTIRAHAT (13)
    (SELECT TIME_FORMAT(detail_waktu, '%H:%i')
     FROM tb_record
     WHERE tgl = R.tgl
       AND userid = R.userid
       AND detail_waktu LIKE '%13:%'
     ORDER BY detail_waktu ASC
     LIMIT 1
    ) AS istirahatkonvert,

    -- JAM PULANG (17)
    (SELECT TIME_FORMAT(detail_waktu, '%H:%i')
     FROM tb_record
     WHERE tgl = R.tgl
       AND userid = R.userid
       AND detail_waktu LIKE '%17:%'
     ORDER BY detail_waktu DESC
     LIMIT 1
    ) AS pulangkonvert,

    B.nama_karyawan,
    B.upah_harian,
    D.nama_departmen

FROM (
    SELECT DISTINCT tgl, userid
    FROM tb_record
    WHERE tgl BETWEEN '$ttgl1' AND '$ttgl2'
) R
LEFT JOIN ms_karyawan B ON R.userid = B.no_absen
LEFT JOIN ms_departmen D ON B.id_departmen = D.id_departmen
WHERE B.nama_karyawan <> ''
ORDER BY R.tgl, B.nama_karyawan;


 ");
}

    ?>

<div class="row px-3 mt-4">
    <div class="col-md-12">
        <div class="card rounded-2xl shadow-sm border-0">
            <div class="card-header bg-white border-b border-gray-100 py-4 flex justify-between items-center rounded-t-2xl">
                <h3 class="card-title text-xl font-bold text-gray-800 m-0">Data Absensi Karyawan</h3>
                <div class="card-tools">
                    <form method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                        <!-- We extract the hidden/selected filters so that export buttons work on the current filter, or we just rely on standard POST -->
                        <button type="submit" name="excel" value="Excel" class="btn btn-success bg-green-500 hover:bg-green-600 border-0 rounded-lg shadow-sm px-4 py-2 font-medium transition-colors text-white">
                            <i class="fas fa-file-excel mr-2"></i> Export Excel
                        </button>
                        <a href="?page=absen&aksi=cari" class="btn btn-primary bg-brand-600 hover:bg-brand-700 border-0 rounded-lg shadow-sm px-4 py-2 font-medium transition-colors text-white">
                            <i class="fas fa-plus mr-2"></i> Input Manual
                        </a>
                    </form>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Search Filter Form -->
                <form method="POST" enctype="multipart/form-data" class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100 shadow-sm">
                    <div class="row items-end"> 
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="font-medium text-gray-700 text-sm mb-1">Dari Tanggal</label>
                            <input autocomplete="off" type="date" name="ttgl1" required value="<?php echo $ttgl1 ; ?>" class="form-control rounded-lg border-gray-300 focus:border-brand-500 focus:ring focus:ring-brand-200 transition-all shadow-sm"/>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="font-medium text-gray-700 text-sm mb-1">Sampai Tanggal</label>
                            <input autocomplete="off" type="date" name="ttgl2" value="<?php echo $ttgl2 ; ?>" required class="form-control rounded-lg border-gray-300 focus:border-brand-500 focus:ring focus:ring-brand-200 transition-all shadow-sm"/>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="font-medium text-gray-700 text-sm mb-1">Bagian</label>
                            <select class="form-control rounded-lg border-gray-300 focus:border-brand-500 focus:ring focus:ring-brand-200 transition-all shadow-sm" name="tdepartmen" required>
                                <option value="<?php echo $tiddepartmen ?>"><?php echo $tnamadepartmen ?></option>
                                <?php 
                                $sql = $koneksi->query("select '99' as id_departmen , 'All' as nama_departmen UNION ALL SELECT A.id_departmen , A.nama_departmen from ms_departmen A");
                                while ($dataRow =  $sql->fetch_array()) {
                                    if (isset($dataBagian) && $dataBagian == $dataRow['nama_departmen']) {
                                        $cek1 = " selected";
                                    } else { 
                                        $cek1=""; 
                                    }
                                    echo "<option value='".$dataRow['id_departmen']."' ".$cek1.">".$dataRow['nama_departmen']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="simpan" value="Search" class="btn btn-primary w-full bg-slate-800 hover:bg-slate-900 border-0 rounded-lg shadow-sm font-medium transition-colors">
                                <i class="fas fa-search mr-1"></i> Tampilkan
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-striped border-b border-gray-100" id="dataTables-example">
                        <thead class="bg-gray-50 text-gray-600 text-sm">
                                        <tr>
                                        <th width="5%">No</th>
                                           <th >NIK</th>
                                            <th >Nama Karyawan</th>
                                            <th >Nama Bagian</th>
                                            
                                              <th >Tanggal</th>
                                            
                                                <th >Absen Masuk</th>


                                        <th >Absen Keluar</th>
                                       
                        

                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 1;


    while ($data=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $data['userid'] ?></td>
<td><?php echo $data['nama_karyawan'] ?></td>
<td><?php echo htmlspecialchars( isset($data['namadepartmen']) ? $data['namadepartmen'] : '—' ); ?></td>

<td><?php echo $data['tgl'] ?></td>
<td><?php echo $data['masukkonvert'] ?></td>
<td><?php echo $data['pulangkonvert'] ?></td>


<!--
<td>
    
<a  href="?page=order&id=<?php echo $data['id_transaksi'];?>"  class="btn btn-success"> Update</a>


</td>
-->
                                      
                            </tr>

                                        <?php  $no++; } ?>

                                    </tbody>   
                                </table>
                            </div>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div>
        </div>
    </div>
  
    <script>
 $(document).ready( function () {
$('#dataTables-example').DataTable({
    pageLength: 100,
    "searching": true
}
);

} );
</script>

<?php

$ttgl1 = @$_POST ['ttgl1'];
$ttgl2 = @$_POST ['ttgl2'];
$tdepartmen = @$_POST ['tdepartmen'];

$simpan = @$_POST ['simpan'];
$print = @$_POST ['print'];
$excel = @$_POST ['excel'];
if($simpan){
    ?><script type="text/javascript">
                 window.location.href="?page=absen&ttgl1=<?php echo $ttgl1 ; ?>&ttgl2=<?php echo$ttgl2 ; ?>&tdepartmen=<?php echo $tdepartmen ?>";

            </script>
            <?php
}
if($print){
    ?><script type="text/javascript">
                 window.location.href="laporanpendapatan.php?ttgl1=<?php echo $ttgl1 ; ?>&ttgl2=<?php echo$ttgl2 ; ?>&tdepartmen=<?php echo $tdepartmen ?>";

            </script>
            <?php
}
if($excel){
    ?><script type="text/javascript">
                 window.location.href="excel.php?ttgl1=<?php echo $ttgl1 ; ?>&ttgl2=<?php echo$ttgl2 ; ?>&tdepartmen=<?php echo $tdepartmen ?>";

            </script>
            <?php
}
 


?>