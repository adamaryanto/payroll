<div class="container-fluid py-4">
    <div class="card-modern">
        <div class="card-modern-header">
            <h3 class="card-modern-title">
                <i class="fas fa-building" style="color:#2563eb;"></i> Master Data Upah
            </h3>
            <a href="?page=upah&aksi=tambah" class="btn-modern">
                <i class="fas fa-plus"></i> Tambah Data Upah
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-modern w-full" id="dataTables-example">
                <thead class="bg-gray-50 text-gray-600 text-sm">
                    <tr>
                        <th width="5%">No</th>
                        <th>Upah Harian</th>
                        <th>Upah Mingguan</th>
                        <th>Upah Bulanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $tampil = $koneksi->query("SELECT * FROM ms_upah ORDER BY id_upah DESC");
                    while ($data = $tampil->fetch_assoc()) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $no; ?>
                        </td>
                        <td> Rp <?php echo number_format($data['upah_harian'], 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($data['upah_mingguan'], 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($data['upah_bulanan'], 0, ',', '.'); ?></td>
                        <td>
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
    .card-modern {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0f0f0;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .card-modern-header {
        background-color: #ffffff;
        padding: 20px 24px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-modern-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-modern {
        background-color: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        font-size: 13px;
        transition: all 0.2s;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-modern:hover {
        background-color: #1e3a8a;
        color: white;
    }

    .table-responsive {
        padding: 24px;
    }

    .table-modern thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.05em;
        padding: 12px 16px;
        border-bottom: 2px solid #f1f5f9;
    }

    .table-modern tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 14px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    .action-links {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
        text-decoration: none !important;
    }

    .btn-edit {
        background-color: #fef3c7;
        color: #d97706;
    }

    .btn-delete {
        background-color: #fee2e2;
        color: #dc2626;
    }

    /* 1. Reset wrapper agar tidak menggunakan float bawaan DataTables */
    .dataTables_wrapper {
        display: block !important;
    }

    /* 2. Memaksa area atas (Length & Filter) menjadi satu baris sejajar */
    .dataTables_wrapper::before,
    .dataTables_wrapper::after {
        display: none !important;
    }

    /* 3. Membuat container fleksibel untuk Length (kiri) dan Filter (kanan) */
    #dataTables-example_wrapper .row:first-child {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 20px !important;
        width: 100% !important;
    }

    /* 4. Styling Tampil _MENU_ (Kiri) */
    .dataTables_length {
        display: flex !important;
        align-items: center !important;
    }

    .dataTables_length label {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 0 !important;
    }

    .dataTables_length select {
        padding: 5px 10px !important;
        border: 1px solid #e0e6ed !important;
        border-radius: 8px !important;
    }

    /* 5. Styling Cari: (Kanan) */
    .dataTables_filter {
        text-align: right !important;
        display: flex !important;
        justify-content: flex-end !important;
    }

    .dataTables_filter label {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin: 0 !important;
    }

    .dataTables_filter input {
        padding: 6px 12px !important;
        border: 1px solid #e0e6ed !important;
        border-radius: 8px !important;
        width: 200px !important;
    }

    /* --- STYLING PAGINATE (PREV/NEXT) --- */
    .dataTables_wrapper .dataTables_paginate {
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        gap: 4px !important;
        padding-top: 15px !important;
    }

    .dataTables_paginate .paginate_button {
        border: 1px solid #e2e8f0 !important;
        background: white !important;
        border-radius: 6px !important;
        padding: 5px 12px !important;
        color: #475569 !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
    }

    .dataTables_paginate .paginate_button:hover {
        background: #f8fafc !important;
        color: #2563eb !important;
        border-color: #cbd5e1 !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: white !important;
    }

    .dataTables_paginate .paginate_button.disabled {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        cursor: not-allowed !important;
    }

    /* --- STYLING INFO --- */
    .dataTables_wrapper .dataTables_info {
        padding-top: 20px !important;
        color: #64748b !important;
        font-size: 13px !important;
    }

    @media screen and (max-width: 768px) {
        .table-responsive {
            padding: 12px !important;
        }

        .table-modern thead {
            display: none !important;
        }

        .table-modern tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px;
        }

        .table-modern tbody td {
            display: flex;
            align-items: flex-start;
            padding: 8px 10px !important;
            border: none !important;
            border-bottom: 1px solid #f3f4f6 !important;
        }

        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            font-size: 11px;
            min-width: 120px;
            margin-right: 15px;
        }
    }
</style>


<script>
    $(document).ready(function() {
        var table = $('#dataTables-example').DataTable({
            pageLength: 25,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            language: {
                search: "Cari:",
                searchPlaceholder: "Cari...",
                lengthMenu: "Tampilkan _MENU_ data upah",
                info: "Menampilkan _START_ sd _END_ dari _TOTAL_ data upah"
            },
            initComplete: function() {
                // Memindahkan posisi secara paksa setelah tabel ter-render
                var $wrapper = $('#dataTables-example_wrapper');
                var $length = $wrapper.find('.dataTables_length');
                var $filter = $wrapper.find('.dataTables_filter');

                // Membuat wadah flex baru di atas tabel
                $('<div class="d-flex justify-content-between align-items-center mb-3"></div>')
                    .insertBefore('#dataTables-example_wrapper .row:first-child')
                    .append($length)
                    .append($filter);
            }
        });
    });
</script>
