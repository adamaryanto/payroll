<style>
  /* Card Wrapper Modern */
.custom-card {
    border-radius: 12px !important;
    border: none !important;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    overflow: hidden;
    margin-bottom: 30px;
}

/* Header dengan Gradasi Halus */
.custom-header {
    background: linear-gradient(45deg, #5F9EA0, #4d8284) !important;
    color: white !important;
    padding: 15px 20px !important;
    border: none !important;
}

.custom-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Form Styling */
.form-container {
    padding: 25px !important;
    background-color: #fff;
}

.custom-input {
    border-radius: 8px !important;
    border: 1px solid #e0e0e0 !important;
    padding: 10px 15px !important;
    height: auto !important; /* Biar tidak terlalu gepeng */
    transition: all 0.3s;
}

.custom-input:focus {
    border-color: #5F9EA0 !important;
    box-shadow: 0 0 0 3px rgba(95, 158, 160, 0.2) !important;
}

.label-text {
    font-size: 13px;
    text-transform: uppercase;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

/* Tombol */
.btn-save {
    background-color: #5F9EA0 !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 25px !important;
    font-weight: 600;
    transition: 0.3s;
}

.btn-save:hover {
    background-color: #4d8284 !important;
    transform: translateY(-2px);
}
</style>

<div class="row">
    <div class="col-md-10 col-md-offset-1"> <div class="panel panel-default custom-card">
            <div class="panel-heading custom-header">
                <h3><i class="fa fa-calendar-check-o"></i> Form Rencana Kerja</h3>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="panel-body form-container">
                    <div class="row">
                        <input type="hidden" name="tid">

                        <div class="form-group col-md-3">
                            <label class="label-text">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="ttgl1" value="<?= date('Y-m-d'); ?>" required class="form-control custom-input" />
                        </div>

                        <div class="form-group col-md-6">
                            <label class="label-text">Keterangan Tugas <span class="text-danger">*</span></label>
                            <input type="text" name="tketerangan" placeholder="Contoh: Maintenance Server / Input Data" required class="form-control custom-input" autocomplete="off" />
                        </div>

                        <div class="form-group col-md-2">
                            <label class="label-text">Durasi (Jam) <span class="text-danger">*</span></label>
                            <div class="input-group align-items-center">
                                <input type="number" name="tjamkerja" placeholder="0" min="1" max="24" required class="form-control custom-input" />
                                <span class="input-group-addon" style="border-radius: 0 8px 8px 0; background: #f9f9f9;">Jam</span>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            
                            <div class="pull-left">
                                <button type="submit" name="simpan" value="Simpan" class="btn btn-primary btn-save">
                                    <i class="fa fa-check-circle"></i> Simpan Rencana
                                </button>
                                <a href="?page=rkk" class="btn btn-default" style="border-radius: 8px; padding: 10px 20px;">
                                    Batal
                                </a>
                            </div>

                            <div class="pull-right text-muted" style="padding-top: 10px;">
                                <small><i>Tanda <span class="text-danger">*</span> wajib diisi dengan benar.</i></small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>