
<?php
if (isset($_GET['id'])) {
    $idu = $_GET['id'];
    $tampil = $koneksi->query("SELECT * FROM ms_departmen WHERE id_departmen = '$idu'");
    $data = $tampil->fetch_assoc();
    $namadepartment = $data['nama_departmen'];
}
?>

<div class="row px-3 mt-4">
    <div class="col-md-6 col-md-offset-3">
        <div class="card rounded-2xl shadow-sm border-0">
            <div class="card-header bg-white border-b border-gray-100 py-4 rounded-t-2xl">
                <h3 class="card-title text-xl font-bold text-gray-800 m-0">Ubah Data Bagian</h3>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="card-body p-4">
                    <div class="form-group mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bagian <span class="text-red-500">*</span></label>
                        <input placeholder="Masukkan nama bagian..." autocomplete="off" type="text" name="tnama" value="<?php echo $namadepartment; ?>" required class="form-control custom-input" />
                    </div>
                </div>
                
                <div class="card-footer bg-gray-50 p-4 flex gap-3 justify-end rounded-b-2xl">
                    <button type="submit" name="simpan" class="btn btn-primary bg-brand-600 hover:bg-brand-700 border-0 rounded-lg px-6 py-2 font-semibold shadow-sm transition-all focus:ring-2 focus:ring-brand-500">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                    <a onclick="history.back();" href="#" class="btn btn-danger bg-gray-200 hover:bg-gray-300 text-gray-700 border-0 rounded-lg px-6 py-2 font-semibold transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-input {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        transition: all 0.2s;
    }
    .custom-input:focus {
        border-color: #5F9EA0;
        box-shadow: 0 0 0 3px rgba(95, 158, 160, 0.1);
        outline: none;
    }
    .bg-brand-600 {
        background-color: #5F9EA0 !important;
    }
    .hover\:bg-brand-700:hover {
        background-color: #4d8284 !important;
    }
    .flex { display: flex; }
    .gap-3 { gap: 0.75rem; }
    .justify-end { justify-content: flex-end; }
    .block { display: block; }
    .mb-2 { margin-bottom: 0.5rem; }
    .mb-4 { margin-bottom: 1rem; }
    .p-4 { padding: 1rem; }
    .text-sm { font-size: 0.875rem; }
    .font-semibold { font-weight: 600; }
    .text-gray-700 { color: #374151; }
    .text-gray-800 { color: #1f2937; }
    .text-red-500 { color: #ef4444; }
</style>

<?php
$tnama = @$_POST['tnama'];
$simpan = @$_POST['simpan'];
if ($simpan) {
    $sql = $koneksi->query("UPDATE ms_departmen SET nama_departmen = '$tnama' WHERE id_departmen = '$idu'");
    if ($sql) {
        ?>
        <script type="text/javascript">
            alert("Data Tersimpan");
            window.location.href = "?page=bagian";
        </script>
        <?php
    }
}
?>