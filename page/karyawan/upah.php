<?php


if(isset($_GET['id'])){
    $idu = $_GET['id'];
$tampil=$koneksi->query("SELECT ms_karyawan.* , ms_departmen.nama_departmen, ms_departmen.id_departmen , tb_jadwal.keterangan from ms_karyawan LEFT JOIN ms_departmen on ms_karyawan.id_departmen = ms_departmen.id_departmen 
left join tb_jadwal on ms_karyawan.id_jadwal = tb_jadwal.id_jadwal
  WHERE id_karyawan = '$idu' ");
$data=$tampil->fetch_assoc();
$idkaryawan =$data['id_karyawan'];
$iddepartmen =$data['id_departmen'];
$namadepartmen =$data['nama_departmen'];
$noabsen = $data['no_absen'];
$namakaryawan = $data['nama_karyawan'];
$tempatlahir = $data['tempat_lahir'];
$tgllahir = $data['tgl_lahir'];
$agama = $data['agama'];
$statuskawin = $data['status_kawin'];
$jeniskelamin = $data['jenis_kelamin'];
$noktp = $data['no_ktp'];
$nonpwp = $data['no_npwp'];
$nobpjs = $data['no_bpjs'];
$nosim = $data['no_sim'];
$alamatktp = $data['alamat_ktp'];
$alamattinggal = $data['alamat_tinggal'];
$statuskaryawan = $data['status_karyawan'];
$tglaktif = $data['tgl_aktif'];
$tglnonaktif = $data['tgl_nonaktif'];
$idjadwal = $data['id_jadwal'];
$keterangan = $data['keterangan'];
$harian = $data['upah_harian'];
$mingguan = $data['upah_mingguan'];
$bulanan = $data['upah_bulanan'];
$foto = $data['foto'];
$contsimpan="hidden";
$contupdate="";
}else{
  $idkaryawan = "";
$noabsen = "";
$namakaryawan = "";
$tempatlahir = "";
$tgllahir = "";
$agama = "";
$statuskawin = "";
$jeniskelamin = "";
$noktp = "";
$nosim = "";
$alamatktp = "";
$alamattinggal = "";
$statuskaryawan = "";
$tglaktif = "";
$tglnonaktif = "";
$foto = "";
$contsimpan="";
$contupdate="hidden";
$idjadwal = "";
$keterangan = "";
$harian = "";
$mingguan = "";
$bulanan = "";
}


?>
<div class="container-fluid px-4 mt-5 mb-5">
    <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
        
        <div class="card-header bg-white border-b border-gray-100 py-5 px-6">
            <h3 class="text-2xl font-extrabold text-gray-800 tracking-tight m-0">Pengaturan Upah Karyawan</h3>
            <p class="text-sm text-gray-500 mt-1">Kelola dan perbarui rincian upah untuk karyawan.</p>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="card-body p-6 bg-gray-50/30">
                
                <div class="mb-8">
                    <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-lg"></i> Info Karyawan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        
                        <input type="hidden" name="tid" value="<?php echo $idkaryawan; ?>">

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. Absen</label>
                            <input type="text" name="tnoabsen" value="<?php echo $noabsen; ?>" readonly class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 font-bold cursor-not-allowed">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Karyawan</label>
                            <input autocomplete="off" type="text" name="tnama" value="<?php echo $namakaryawan; ?>" readonly class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-gray-700 font-bold cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2 text-lg"></i> Detail Upah Dasar
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upah Harian <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="tharian" value="<?php echo $harian; ?>" required class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all outline-none" placeholder="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upah Mingguan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="tmingguan" value="<?php echo $mingguan; ?>" required class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all outline-none" placeholder="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upah Bulanan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="tbulanan" value="<?php echo $bulanan; ?>" required class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition-all outline-none" placeholder="0">
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="card-footer bg-gray-50 px-8 py-5 flex items-center justify-between border-t border-gray-200">
                <p class="text-xs text-gray-400 italic">Tanda <span class="text-red-500 font-bold">*</span> wajib diisi.</p>
                <div class="flex gap-3">
                    <a href="?page=karyawan" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-100 transition-all no-underline">
                        Batal
                    </a>
                    <button type="submit" name="update" value="Update" class="px-8 py-2.5 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i> Simpan Upah
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<?php

$tid = @$_POST['tid'] ;
$tnama = @$_POST ['tnama'];
$tshift = @$_POST ['tshift'];
$tnoabsen = @$_POST ['tnoabsen'];
$ttanggallahir = @$_POST ['ttanggallahir'];
$tagama = @$_POST ['tagama'];
$tstatuskawin = @$_POST ['tstatuskawin'];
$tjeniskelamin = @$_POST ['tjeniskelamin'];
$tnoktp = @$_POST ['tnoktp'];
$tnosim = @$_POST ['tnosim'];
$talamatktp = @$_POST ['talamatktp'];
$talamattinggal = @$_POST ['talamattinggal'];
$ttanggalbergabung = @$_POST ['ttanggalbergabung'];
$tharian = @$_POST ['tharian'];
$tmingguan = @$_POST ['tmingguan'];
$tbulanan = @$_POST ['tbulanan'];
$tbpjs = @$_POST ['tbpjs'];
$tnpwp = @$_POST ['tnpwp'];
$simpan = @$_POST ['simpan'];
$update = @$_POST ['update'];
$iduser = $_SESSION['iduser'];
if($simpan) {
$sql = $koneksi->query("insert into ms_karyawan(id_departmen,id_jabatan,no_absen,nama_karyawan,tempat_lahir,tgl_lahir,agama,status_kawin,jenis_kelamin,no_ktp,no_sim,alamat_ktp,alamat_tinggal,status_karyawan,tgl_aktif,no_npwp,no_bpjs) values('0','0','$tnoabsen','$tnama','$ttempatlahir','$ttanggallahir','$tagama','$tstatuskawin','$tjeniskelamin','$tnoktp','$tnosim','$talamatktp','$talamattinggal','Aktif','$ttanggalbergabung','$tnpwp','$tbpjs')  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Data Tersimpan");
                window.location.href="?page=karyawan";

            </script>
            <?php
    }
}//simpan if
elseif($update) {
$sql = $koneksi->query("update ms_karyawan set  upah_harian = '$tharian' , upah_mingguan = '$tmingguan' , upah_bulanan = '$tbulanan' where id_karyawan = '$idu'  ");
if($sql) {
        ?>
                <script type="text/javascript">
                alert("Shift Berhasil Di Atur");
                window.location.href="?page=karyawan";

            </script>
            <?php
    }
}//update if
?>