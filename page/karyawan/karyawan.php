<style>
    /* Kustomisasi DataTables Pagination agar mirip Tailwind */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5em 0.75em !important;
        margin-left: 0.25rem !important;
        border-radius: 0.375rem !important;
        border: 1px solid #e5e7eb !important;
        background: white !important;
        color: #374151 !important;
        font-size: 0.875rem !important;
        transition: all 0.2s ease-in-out !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        color: #111827 !important;
        border-color: #d1d5db !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #3b82f6 !important; /* Warna biru Tailwind (blue-500) */
        color: white !important;
        border-color: #3b82f6 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
        opacity: 0.5 !important;
        background: #f9fafb !important;
        cursor: not-allowed !important;
        color: #9ca3af !important;
    }
    /* Kustomisasi search box & length menu */
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        margin-left: 0.5rem;
        outline: none;
    }
    .dataTables_wrapper .dataTables_filter input:focus,
    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
</style>

<div class="row px-4 mt-6">
    <div class="col-md-12">
        <div class="card rounded-xl shadow-md border border-gray-200 overflow-hidden bg-white">
            
            <div class="card-header bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h3 class="card-title text-xl font-extrabold text-gray-800 m-0 tracking-tight">Data Karyawan</h3>
                <div class="card-tools">
                    <a href="?page=karyawan&aksi=tambah" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200 text-decoration-none">
                        <i class="fas fa-plus mr-2"></i> Tambah Data
                    </a>
                </div>
            </div>
            
            <div class="card-body p-6">
                <div class="table-responsive">
                    <table class="table w-full text-left border-collapse" id="dataTables-example">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-xs uppercase tracking-wider">
                                <th class="text-center px-4 py-3 font-semibold w-12">No</th>
                                <th class="text-center px-4 py-3 font-semibold">No. Absen</th>
                                <th class="px-4 py-3 font-semibold">Nama Karyawan</th>
                                <th class="text-center px-4 py-3 font-semibold">L/P</th>
                                <th class="text-center px-4 py-3 font-semibold">OS/DHK</th>
                                <th class="text-center px-4 py-3 font-semibold">Golongan</th>
                                <th class="text-center px-4 py-3 font-semibold">Status</th>
                                <th class="text-right px-4 py-3 font-semibold">Upah Harian</th>
                                <th class="text-center px-4 py-3 font-semibold w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            <?php
                            $no = 1;
                            $tampil = $koneksi->query("SELECT ms_karyawan.* FROM ms_karyawan");
                            while ($datakaryawan = $tampil->fetch_assoc()) {
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="text-center px-4 py-3 align-middle"><?php echo $no; ?></td>
                                <td class="text-center px-4 py-3 align-middle font-medium"><?php echo $datakaryawan['no_absen']; ?></td>
                                <td class="px-4 py-3 align-middle font-semibold text-gray-900"><?php echo $datakaryawan['nama_karyawan']; ?></td>
                                <td class="text-center px-4 py-3 align-middle">
                                    <?php 
                                        if ($datakaryawan['jenis_kelamin'] == 'Laki-laki') {
                                            echo '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-bold text-xs">L</span>';
                                        } elseif ($datakaryawan['jenis_kelamin'] == 'Perempuan') {
                                            echo '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-pink-100 text-pink-700 font-bold text-xs">P</span>';
                                        } else {
                                            echo '<span class="text-gray-400">-</span>'; 
                                        }
                                    ?>
                                </td>
                                <td class="text-center px-4 py-3 align-middle">
                                    <?php if($datakaryawan['OS_DHK'] == 'OS'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">OS</span>
                                    <?php elseif($datakaryawan['OS_DHK']): ?>
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800"><?php echo $datakaryawan['OS_DHK']; ?></span>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center px-4 py-3 align-middle text-gray-600">
                                    <?php echo $datakaryawan['golongan'] ? $datakaryawan['golongan'] : '-'; ?>
                                </td>
                                <td class="text-center px-4 py-3 align-middle">
                                    <?php if(trim($datakaryawan['status_karyawan']) == 'Aktif'): ?>
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">Aktif</span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-rose-100 text-rose-800">
                                            <?php echo $datakaryawan['status_karyawan'] ? $datakaryawan['status_karyawan'] : 'Non-Aktif'; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right px-4 py-3 align-middle font-medium text-gray-700">
                                    Rp <?php echo number_format($datakaryawan['upah_harian'], 0, ',', '.'); ?>
                                </td>
                                <td class="text-center px-4 py-3 align-middle">
                                    <div class="flex justify-center items-center gap-1.5">
                                        <a href="?page=karyawan&aksi=upah&id=<?php echo $datakaryawan['id_karyawan']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 transition-colors" title="Atur Upah">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                        <a href="?page=karyawan&aksi=view&id=<?php echo $datakaryawan['id_karyawan']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors" title="Lihat Profil">
                                            <i class="fas fa-user"></i>
                                        </a>
                                        <a href="?page=karyawan&aksi=ubah&id=<?php echo $datakaryawan['id_karyawan']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded text-amber-600 hover:bg-amber-100 hover:text-amber-700 transition-colors" title="Ubah Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?page=karyawan&aksi=hapus&id=<?php echo $datakaryawan['id_karyawan']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded text-rose-600 hover:bg-rose-100 hover:text-rose-700 transition-colors" title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
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
</div>

<script>
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            pageLength: 100,
            searching: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
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