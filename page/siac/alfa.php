<?php
// Query Data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $tampil = $koneksi->query("SELECT A.*, B.nama_karyawan, B.no_absen, B.jenis_kelamin 
                               FROM tb_alfa A 
                               LEFT JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan 
                               WHERE A.id_karyawan = '$id'");
} else {
    $tampil = $koneksi->query("SELECT A.*, B.nama_karyawan, B.no_absen, B.jenis_kelamin 
                               FROM tb_alfa A 
                               LEFT JOIN ms_karyawan B ON A.id_karyawan = B.id_karyawan");
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <div class="bg-white shadow-sm border border-gray-300 rounded-lg overflow-hidden">
        
        <div class="bg-slate-700 border-b border-slate-800 px-6 py-4">
            <h3 class="text-xl font-bold text-white m-0 tracking-tight">
                Data Karyawan Alfa
            </h3>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <a href="?page=siac" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-semibold rounded-md shadow-sm text-white bg-sky-500 hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300" id="dataTables-example">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="border border-gray-300 px-4 py-3 text-left text-sm font-bold text-gray-900 uppercase tracking-wider w-12">No</th>
                            <th scope="col" class="border border-gray-300 px-4 py-3 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">No. Absen</th>
                            <th scope="col" class="border border-gray-300 px-4 py-3 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Nama Karyawan</th>
                            <th scope="col" class="border border-gray-300 px-4 py-3 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Jenis Kelamin</th>
                            <th scope="col" class="border border-gray-300 px-4 py-3 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Dari Tanggal</th>
                            <th scope="col" class="border border-gray-300 px-4 py-3 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Sampai Tanggal</th>
                            <th scope="col" class="border border-gray-300 px-4 py-3 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Lama</th>
                            <th scope="col" class="border border-gray-300 px-4 py-3 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Keterangan Alfa</th>
                            <th scope="col" class="border border-gray-300 px-4 py-3 text-center text-sm font-bold text-gray-900 uppercase tracking-wider w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <?php
                        $no = 1;
                        while ($data = $tampil->fetch_assoc()) {
                            // Kalkulasi hari
                            $tgl1 = strtotime($data['tgl_awal_alfa']); 
                            $tgl2 = strtotime($data['tgl_akhir_alfa']); 
                            $jarak = $tgl2 - $tgl1;
                            $hari = ($jarak / 60 / 60 / 24) + 1;
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors duration-200">
                            <td class="border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-800 text-center">
                                <?php echo $no; ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-800">
                                <?php echo htmlspecialchars($data['no_absen']); ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-800">
                                <?php echo htmlspecialchars($data['nama_karyawan']); ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-800 text-center">
                                <?php echo htmlspecialchars($data['jenis_kelamin']); ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-800">
                                <?php echo htmlspecialchars($data['tgl_awal_alfa']); ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-800">
                                <?php echo htmlspecialchars($data['tgl_akhir_alfa']); ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-800 text-center">
                                <?php echo $hari; ?> Hari
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-800">
                                <?php echo htmlspecialchars($data['keterangan_alfa']); ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-center">
                                <a href="?page=alfa&aksi=hapus&id=<?php echo $data['id_alfa']; ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" onclick="return confirm('Yakin ingin menghapus data ini?');">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php 
                            $no++; 
                        } 
                        ?>
                    </tbody>   
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .dataTables_wrapper {
        padding-top: 1rem;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
        outline: none;
        color: #111827;
        font-weight: 500;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #334155; /* slate-700 */
        box-shadow: 0 0 0 2px rgba(51, 65, 85, 0.25);
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        outline: none;
        font-weight: 500;
    }

    /* Styling Teks Showing 1 to X */
    .dataTables_wrapper .dataTables_info {
        padding-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
        font-size: 0.875rem; 
        font-weight: 600; 
        color: #374151; 
        float: left;
    }

    /* Styling Pagination */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1.25rem !important;
        margin-bottom: 1.5rem !important;
        font-size: 0.875rem;
        font-weight: 600;
        float: right;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.875rem !important;
        margin: 0 0.25rem !important; 
        border-radius: 0.375rem;
        border: 1px solid #d1d5db !important;
        background: #ffffff !important;
        color: #374151 !important;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-block; 
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
        color: #111827 !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #334155 !important; 
        color: #ffffff !important;
        border-color: #334155 !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f9fafb !important;
        color: #9ca3af !important;
    }
</style>

<script>
$(document).ready(function() {
    $('#dataTables-example').DataTable({
        pageLength: 10,
        searching: true,
        ordering: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>

<?php
// Logika Proses Laporan
$ttgl1 = @$_POST['ttgl1'];
$ttgl2 = @$_POST['ttgl2'];

$simpan = @$_POST['simpan'];
$print = @$_POST['print'];
$excel = @$_POST['excel'];

if ($simpan) {
    echo "<script type='text/javascript'>window.location.href='?page=alfa&ttgl1=$ttgl1&ttgl2=$ttgl2';</script>";
}
if ($print) {
    echo "<script type='text/javascript'>window.location.href='laporanpendapatan.php?ttgl1=$ttgl1&ttgl2=$ttgl2';</script>";
}
if ($excel) {
    echo "<script type='text/javascript'>window.location.href='excelpendapatan.php?ttgl1=$ttgl1&ttgl2=$ttgl2';</script>";
}
?>