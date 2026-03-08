<?php
if(isset($_GET['id'])){
    $idjadwal = $_GET['id'];
    $tampildetail = $koneksi->query("SELECT * FROM tb_jadwal WHERE id_jadwal = '$idjadwal'");
    $datadetail = $tampildetail->fetch_assoc();
} else {
    echo "<script>window.location.href='?page=jadwal';</script>";
    exit;
}
?>

<style>
    .card-modern { background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); border: 1px solid #f0f0f0; margin-bottom: 24px; padding: 24px; }
    .card-modern-header { margin-bottom: 24px; border-bottom: 2px solid #f8f9fa; padding-bottom: 12px; }
    .card-modern-title { font-size: 18px; font-weight: 600; color: #2c3e50; margin: 0; display: flex; align-items: center; gap: 8px; }
    
    .form-modern label { font-weight: 500; color: #5a6a85; font-size: 13px; margin-bottom: 6px; display: block; }
    .form-modern .form-control { border-radius: 8px; border: 1px solid #e0e6ed; padding: 10px 14px; font-size: 14px; background-color: #fafbfc; transition: all 0.3s; }
    .form-modern .form-control:focus { border-color: #2563eb; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(95, 158, 160, 0.15); outline: none; }
    
    .btn-modern { background-color: #2563eb; color: white; border: none; border-radius: 8px; padding: 10px 24px; font-weight: 500; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .btn-modern:hover { background-color: #1e3a8a; color: white; }
    
    .required-text { font-size: 12px; color: #8898aa; margin-top: 10px; }
    .required-star { color: #e74c3c; font-weight: bold; }
    .form-group { margin-bottom: 16px; }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card-modern">
            <div class="card-modern-header">
                <h3 class="card-modern-title">
                    <i class="fa fa-edit" style="color:#2563eb;"></i> Ubah Jadwal Karyawan
                </h3>
            </div>
            
            <form method="POST" class="form-modern">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Nama Shift <span class="required-star">*</span></label>
                        <input autocomplete="off" type="text" name="tketerangan" value="<?php echo $datadetail['keterangan']; ?>" required class="form-control" />
                    </div>
                    <div class="form-group col-md-4">
                        <label>Jam Masuk <span class="required-star">*</span></label>
                        <input autocomplete="off" type="time" name="tjammasuk" value="<?php echo $datadetail['jam_masuk']; ?>" required class="form-control" />
                    </div>
                    <div class="form-group col-md-4">
                        <label>Jam Keluar <span class="required-star">*</span></label>
                        <input autocomplete="off" type="time" name="tjamkeluar" value="<?php echo $datadetail['jam_keluar']; ?>" required class="form-control" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Istirahat Keluar <span class="required-star">*</span></label>
                        <input autocomplete="off" type="time" name="tistirahatkeluar" value="<?php echo $datadetail['istirahat_keluar']; ?>" required class="form-control" />
                    </div>
                    <div class="form-group col-md-4">
                        <label>Istirahat Masuk <span class="required-star">*</span></label>
                        <input autocomplete="off" type="time" name="tistirahatmasuk" value="<?php echo $datadetail['istirahat_masuk']; ?>" required class="form-control" />
                    </div>
                </div>

                <hr style="border-top: 1px dashed #e0e6ed; margin: 24px 0;" />

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" name="simpan" value="Simpan" class="btn-modern">
                            <i class="fa fa-save"></i> Perbarui Jadwal
                        </button>
                        <div class="required-text">
                            <span class="required-star">*</span> Kolom wajib diisi
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if(isset($_POST['simpan'])) {
    $keterangan = $_POST['tketerangan'];
    $jammasuk   = $_POST['tjammasuk'];
    $jamkeluar  = $_POST['tjamkeluar'];
    $istkeluar  = $_POST['tistirahatkeluar'];
    $istmasuk   = $_POST['tistirahatmasuk'];

    $sql = $koneksi->query("UPDATE tb_jadwal SET 
                            keterangan = '$keterangan', 
                            jam_masuk = '$jammasuk', 
                            jam_keluar = '$jamkeluar', 
                            istirahat_masuk = '$istmasuk', 
                            istirahat_keluar = '$istkeluar' 
                            WHERE id_jadwal = '$idjadwal'");
    
    if($sql) {
        echo '<script>alert("Data Berhasil Diperbarui!"); window.location.href="?page=jadwal";</script>';
    }
}
?>