<style>
    /* Desain Card Modern & Minimalis */
    .card-modern {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* Shadow lembut */
        border: 1px solid #f0f0f0;
        margin-bottom: 24px;
        padding: 24px;
    }
    
    .card-modern-header {
        margin-bottom: 24px;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 12px;
    }

    .card-modern-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Styling Form Input yang Clean */
    .form-modern label {
        font-weight: 500;
        color: #5a6a85;
        font-size: 13px;
        margin-bottom: 6px;
        display: block;
    }

    .form-modern .form-control {
        border-radius: 8px;
        border: 1px solid #e0e6ed;
        box-shadow: none;
        padding: 10px 14px;
        font-size: 14px;
        color: #333;
        transition: all 0.3s ease;
        background-color: #fafbfc;
    }

    .form-modern .form-control:focus {
        border-color: #5F9EA0;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(95, 158, 160, 0.15); /* Glow effect saat aktif */
        outline: none;
    }

    /* Styling Tombol Modern */
    .btn-modern {
        background-color: #5F9EA0; /* Warna cadillac cadet blue */
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-modern:hover {
        background-color: #4a8082;
        color: white;
        box-shadow: 0 4px 8px rgba(95, 158, 160, 0.2);
    }

    .required-text {
        font-size: 12px;
        color: #8898aa;
        margin-top: 10px;
    }

    .required-star {
        color: #e74c3c;
        font-weight: bold;
    }
    
    /* Spacing untuk grid bootstrap */
    .form-modern .form-group {
        margin-bottom: 16px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card-modern">
            
            <div class="card-modern-header">
                <h3 class="card-modern-title">
                    <i class="fa fa-calendar-alt" style="color:#5F9EA0;"></i> Ubah Jadwal Karyawan
                </h3>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="form-modern">
                <div class="row">
                    <div hidden="hidden" class="form-group col-md-4">
                        <label>Id Karyawan</label>
                        <input autocomplete="off" type="text" name="tid" class="form-control" />
                    </div>

                    <div class="form-group col-md-4">
                        <label>Nama Shift <span class="required-star">*</span></label>
                        <input placeholder="Contoh: Shift Pagi" autocomplete="off" type="text" name="tketerangan" required class="form-control" />
                    </div>

                    <div class="form-group col-md-4">
                        <label>Jam Masuk <span class="required-star">*</span></label>
                        <input autocomplete="off" type="time" name="tjammasuk" required class="form-control" />
                    </div>

                    <div class="form-group col-md-4">
                        <label>Jam Keluar <span class="required-star">*</span></label>
                        <input autocomplete="off" type="time" name="tjamkeluar" required class="form-control" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Istirahat Keluar <span class="required-star">*</span></label>
                        <input autocomplete="off" type="time" name="tistirahatkeluar" required class="form-control" />
                    </div>

                    <div class="form-group col-md-4">
                        <label>Istirahat Masuk <span class="required-star">*</span></label>
                        <input autocomplete="off" type="time" name="tistirahatmasuk" required class="form-control" />
                    </div>
                </div>

                <hr style="border-top: 1px dashed #e0e6ed; margin: 24px 0;" />

                <div class="row">
                    <div class="col-md-12 d-flex align-items-center justify-content-between">
                        <div>
                            <button type="submit" name="simpan" value="Simpan" class="btn-modern">
                                <i class="fa fa-save"></i> Simpan Jadwal
                            </button>
                            <div class="required-text">
                                <span class="required-star">*</span> Kolom wajib diisi
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<?php
$tketerangan      = @$_POST['tketerangan'];
$tjammasuk        = @$_POST['tjammasuk'];
$tjamkeluar       = @$_POST['tjamkeluar'];
$tistirahatmasuk  = @$_POST['tistirahatmasuk'];
$tistirahatkeluar = @$_POST['tistirahatkeluar'];
$simpan           = @$_POST['simpan'];

if ($simpan) {
    $sql = $koneksi->query("INSERT INTO tb_jadwal (keterangan, jam_masuk, jam_keluar, istirahat_masuk, istirahat_keluar) 
                            VALUES ('$tketerangan', '$tjammasuk', '$tjamkeluar', '$tistirahatmasuk', '$tistirahatkeluar')");
    
    if ($sql) {
        echo '<script type="text/javascript">
                alert("Data Jadwal Berhasil Disimpan!");
                window.location.href="?page=jadwal";
              </script>';
    }
}
?>