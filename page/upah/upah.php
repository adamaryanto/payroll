<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <div class="bg-white shadow-sm border border-gray-300 rounded-lg overflow-hidden">
        
        <div class="bg-white border-b border-gray-300 px-6 py-5 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900 m-0 tracking-tight">
                Master Template Upah
            </h3>
            <div>
                <a href="?page=upah&aksi=tambah" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Tambah Upah
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300" id="dataTables-example">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="border border-gray-300 px-6 py-4 text-left text-sm font-bold text-gray-900 uppercase tracking-wider w-16">
                            No
                        </th>
                        <th scope="col" class="border border-gray-300 px-6 py-4 text-right text-sm font-bold text-gray-900 uppercase tracking-wider">
                            Upah Harian
                        </th>
                        <th scope="col" class="border border-gray-300 px-6 py-4 text-right text-sm font-bold text-gray-900 uppercase tracking-wider">
                            Upah Mingguan
                        </th>
                        <th scope="col" class="border border-gray-300 px-6 py-4 text-right text-sm font-bold text-gray-900 uppercase tracking-wider">
                            Upah Bulanan
                        </th>
                        <th scope="col" class="border border-gray-300 px-6 py-4 text-center text-sm font-bold text-gray-900 uppercase tracking-wider w-40">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php
                    $no = 1;
                    $tampil = $koneksi->query("SELECT * FROM ms_upah ORDER BY id_upah DESC");
                    while ($data = $tampil->fetch_assoc()) {
                    ?>
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="border border-gray-300 px-6 py-4 text-sm font-semibold text-gray-800 text-center">
                            <?php echo $no; ?>
                        </td>
                        <td class="border border-gray-300 px-6 py-4 text-sm font-semibold text-gray-800 text-right">
                            Rp <?php echo number_format($data['upah_harian'], 0, ',', '.'); ?>
                        </td>
                        <td class="border border-gray-300 px-6 py-4 text-sm font-semibold text-gray-800 text-right">
                            Rp <?php echo number_format($data['upah_mingguan'], 0, ',', '.'); ?>
                        </td>
                        <td class="border border-gray-300 px-6 py-4 text-sm font-semibold text-gray-800 text-right">
                            Rp <?php echo number_format($data['upah_bulanan'], 0, ',', '.'); ?>
                        </td>
                        <td class="border border-gray-300 px-6 py-4 text-center">
                            <a href="?page=upah&aksi=ubah&id=<?php echo $data['id_upah']; ?>" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-bold rounded shadow-sm text-white bg-amber-500 hover:bg-amber-600 transition-colors mr-1" title="Edit">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <a href="?page=upah&aksi=hapus&id=<?php echo $data['id_upah']; ?>" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-bold rounded shadow-sm text-white bg-red-600 hover:bg-red-700 transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus data upah ini?');">
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

<style>
    .dataTables_wrapper { padding: 1.5rem; }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.375rem 0.75rem; margin-left: 0.5rem; outline: none; color: #111827; font-weight: 500;
    }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25); }
    .dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.25rem 0.5rem; outline: none; font-weight: 500; }
    .dataTables_wrapper .dataTables_info { padding-top: 1.5rem !important; margin-bottom: 1.5rem !important; font-size: 0.875rem; font-weight: 600; color: #374151; float: left; }
    .dataTables_wrapper .dataTables_paginate { padding-top: 1.25rem !important; margin-bottom: 1.5rem !important; font-size: 0.875rem; font-weight: 600; float: right; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.375rem 0.875rem !important; margin: 0 0.25rem !important; border-radius: 0.375rem; border: 1px solid #d1d5db !important; background: #fff !important; color: #374151 !important; cursor: pointer; transition: all 0.2s; display: inline-block; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #2563eb !important; color: #fff !important; border-color: #2563eb !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.5; cursor: not-allowed; background: #f9fafb !important; color: #9ca3af !important; }
</style>

<script>
$(document).ready(function() {
    $('#dataTables-example').DataTable({
        pageLength: 10, searching: true, ordering: true,
        language: { search: "Cari:", lengthMenu: "Tampilkan _MENU_ data", info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data", infoEmpty: "Menampilkan 0 sampai 0 dari 0 data", paginate: { first: "Awal", last: "Akhir", next: "Selanjutnya", previous: "Sebelumnya" } }
    });
});
</script>
