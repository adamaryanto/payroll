<div class="container-fluid px-4 mt-5">
    <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
        <div class="card-header bg-white border-b border-gray-100 py-5 px-6">
            <h3 class="text-2xl font-extrabold text-gray-800 m-0">Tambah Data Status Kawin</h3>
        </div>
        <form method="POST">
            <div class="card-body p-6 bg-gray-50/30">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Status <span class="text-red-500">*</span></label>
                        <input autocomplete="off" type="text" name="tstatus" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
            </div>
            <div class="card-footer bg-gray-50 px-8 py-5 flex items-center justify-between border-t border-gray-200">
                <div class="flex gap-3 ml-auto">
                    <a href="?page=statuskawin" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold no-underline">Batal</a>
                    <button type="submit" name="simpan" class="px-8 py-2.5 bg-indigo-600 text-white rounded-lg font-bold shadow-lg transition-all">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
if(isset($_POST['simpan'])) {
    $tstatus = $_POST['tstatus'];
    $sql = $koneksi->query("INSERT INTO ms_status_kawin (status_kawin) VALUES ('$tstatus')");
    if($sql) echo "<script>alert('Berhasil'); window.location='?page=statuskawin';</script>";
}
?>
