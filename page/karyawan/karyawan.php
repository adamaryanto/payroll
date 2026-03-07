<div class="container-fluid px-4 mt-5 mb-5">
    <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
        
        <div class="card-header bg-white border-b border-gray-100 py-5 px-6 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-extrabold text-gray-800 tracking-tight m-0">Data Karyawan</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola informasi detail dan upah seluruh karyawan</p>
            </div>
            <div class="card-tools">
                <a href="?page=karyawan&aksi=tambah" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-lg transition duration-200 ease-in-out transform hover:-translate-y-0.5 shadow-md">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tambah Karyawan
                </a>
            </div>
        </div>
        
        <div class="card-body p-0"> 
            <div class="table-responsive p-4"> <table class="table table-hover align-middle mb-0" id="dataTables-example" style="width:100%">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">No</th>
                            <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Absen</th>
                            <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                            <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Gol</th>
                            <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Jenis Kelamin</th>
                            <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak & Dokumen</th>
                            <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                            <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center" style="min-width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <?php
                        $no = 1;
                        $tampil = $koneksi->query("SELECT * FROM ms_karyawan");
                        while ($datakaryawan = $tampil->fetch_assoc()) {
                            $status_class = ($datakaryawan['status_karyawan'] == 'Aktif') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                        ?>
                        <tr class="hover:bg-blue-50/50 transition-colors">
                            <td class="text-center text-sm text-gray-600 font-medium"><?= $no++ ?></td>
                            <td class="text-sm font-semibold text-gray-700"><?= $datakaryawan['no_absen'] ?></td>
                            <td>
                                <div class="font-bold text-gray-900"><?= $datakaryawan['nama_karyawan'] ?></div>
                                <div class="text-xs text-gray-400 italic"><?= $datakaryawan['OS_DHK'] ?></div>
                            </td>
                            <td class="text-center text-sm font-bold text-indigo-600"><?= $datakaryawan['golongan'] ?></td>
                            <td class="text-center text-sm text-gray-600"><?= $datakaryawan['jenis_kelamin'] ?></td>
                            <td class="text-xs space-y-1">
                                <div class="flex items-center"><span class="w-12 text-gray-400 font-medium">KTP:</span> <span class="text-gray-700"><?= $datakaryawan['no_ktp'] ?></span></div>
                            </td>
                            <td class="text-center">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $status_class ?>">
                                    <?= $datakaryawan['status_karyawan'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="?page=karyawan&aksi=view&id=<?= $datakaryawan['id_karyawan'] ?>" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="?page=karyawan&aksi=ubah&id=<?= $datakaryawan['id_karyawan'] ?>" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-lg transition-all" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?page=karyawan&aksi=hapus&id=<?= $datakaryawan['id_karyawan'] ?>" class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition-all" onclick="return confirm('Hapus data ini?')" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Dasar Table */
    #dataTables-example {
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }

    /* Form Filter & Length Menu */
    .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 4px 8px;
        margin: 0 5px;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 8px !important;
        border: 1px solid #e5e7eb !important;
        padding: 8px 12px !important;
        outline: none;
        transition: all 0.2s;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* PAGINATION STYLING (Previous, Next, Numbers) */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 4px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #e5e7eb !important;
        background: white !important;
        border-radius: 8px !important;
        padding: 6px 14px !important;
        color: #4b5563 !important;
        font-weight: 500 !important;
        transition: all 0.2s ease;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
        color: #111827 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: white !important;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f9fafb !important;
    }

    /* Info text (Showing 1 to 10 of X entries) */
    .dataTables_wrapper .dataTables_info {
        padding-top: 1.7rem !important;
        font-size: 0.875rem;
        color: #6b7280;
    }
</style>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            pageLength: 10,
            responsive: true,
            // Custom text agar lebih user friendly
            language: {
                search: "",
                searchPlaceholder: "Cari data karyawan...",
                lengthMenu: "Tampilkan _MENU_",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ karyawan",
                paginate: {
                    previous: "<i class='fas fa-chevron-left text-xs'></i> Prev",
                    next: "Next <i class='fas fa-chevron-right text-xs'></i>"
                }
            }
        });
        
        // Memindahkan letak search bar ke posisi yang lebih enak dilihat jika perlu
        $('.dataTables_filter').addClass('mb-3');
    });
</script>