<?php
// karyawan.php - Modern Responsive Version
$idrkk = $_GET['id'] ?? '';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-10">
    <div class="bg-white shadow-xl border border-gray-200 rounded-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 md:px-8 py-4 md:py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl md:text-2xl font-extrabold text-white m-0 tracking-tight flex items-center">
                        <i class="fas fa-users mr-3"></i>
                        Data Karyawan Aktif
                    </h3>
                    <p class="text-blue-100 text-xs md:text-sm mt-1">Daftar seluruh karyawan yang terdaftar dalam sistem</p>
                </div>
            </div>
        </div>
        
        <div class="p-3 md:p-6">
            <div class="table-responsive">
                <table class="w-full text-left border-separate border-spacing-y-2 table-modern" id="dataTables-example">
                    <thead>
                        <tr class="text-gray-500 uppercase text-[11px] tracking-wider font-bold">
                            <th hidden>ID Karyawan</th>
                            <th class="px-4 py-3 border-b border-gray-100">No. Absen</th>
                            <th class="px-4 py-3 border-b border-gray-100">Nama Lengkap</th>
                            <th class="px-4 py-3 border-b border-gray-100">Bagian / Dept</th>
                            <th class="px-4 py-3 border-b border-gray-100">Gender</th>
                            <th class="px-4 py-3 border-b border-gray-100 uppercase">Tgl Aktif</th>
                            <th class="px-4 py-3 border-b border-gray-100 text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php
                        $tampil = $koneksi->query("SELECT ms_karyawan.* , ms_departmen.nama_departmen 
                                                 FROM ms_karyawan 
                                                 LEFT JOIN ms_departmen on ms_karyawan.id_departmen = ms_departmen.id_departmen 
                                                 WHERE ms_karyawan.status_karyawan = 'Aktif'");
                        while ($datakaryawan = $tampil->fetch_assoc()) :
                            $id = $datakaryawan['id_karyawan'];
                        ?>
                        <tr class="bg-white hover:bg-blue-50 transition-all duration-200 shadow-sm border border-gray-100 rounded-xl">
                            <td hidden><input type="text" name="tidkaryawan[]" value="<?= $id ?>"/></td>
                            <td data-label="No. Absen" class="px-4 py-4 font-bold text-blue-600 italic">
                                #<?= $datakaryawan['no_absen'] ?>
                            </td>
                            <td data-label="Nama" class="px-4 py-4 font-bold text-gray-800 uppercase">
                                <?= $datakaryawan['nama_karyawan'] ?>
                            </td>
                            <td data-label="Bagian" class="px-4 py-4">
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                    <?= $datakaryawan['nama_departmen'] ?>
                                </span>
                            </td>
                            <td data-label="Gender" class="px-4 py-4 text-gray-600">
                                <?= $datakaryawan['jenis_kelamin'] ?>
                            </td>
                            <td data-label="Tgl Aktif" class="px-4 py-4 text-gray-600">
                                <?= date('d M Y', strtotime($datakaryawan['tgl_aktif'])) ?>
                            </td>
                            <td data-label="Aksi" class="px-4 py-4 md:text-center mt-2 md:mt-0">
                                <a href="?page=realisasi&aksi=slip&id=<?= $id ?>" 
                                   class="inline-flex items-center justify-center w-full md:w-auto px-6 py-3 md:py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs md:text-[11px] font-bold rounded-xl shadow-md transition transform hover:-translate-y-0.5 uppercase tracking-wider">
                                    <i class="fas fa-file-invoice-dollar mr-2"></i> Slip Gaji
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
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

    h3{
        color: #2563eb !important;
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

    /* =========================================
       KHUSUS TAMPILAN MOBILE DIPERBAIKI DI SINI
       ========================================= */
    @media screen and (max-width: 768px) {
        .table-responsive {
            padding: 12px !important;
            border: none !important;
        }
        
        #dataTables-example_wrapper .row:first-child {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 15px;
        }
        .dataTables_filter, .dataTables_length {
            width: 100% !important;
            justify-content: flex-start !important;
        }
        .dataTables_filter input {
            width: 100% !important;
            max-width: 100% !important;
        }
        .dataTables_paginate {
            justify-content: center !important;
            flex-wrap: wrap;
        }

        .table-modern thead {
            display: none !important;
        }

        .table-modern tbody tr {
            display: block;
            margin-bottom: 1.5rem; /* Jarak antar kotak dilebarkan */
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px; /* Jarak padding ke dalam kotak dilebarkan */
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background-color: #fff;
        }

        .table-modern tbody td {
            display: flex;
            flex-direction: column; /* Label di atas, data di bawah (stacking) */
            align-items: flex-start;
            padding: 10px 0 !important; /* Jarak atas-bawah per baris dilebarkan */
            border: none !important;
            border-bottom: 1px dashed #e2e8f0 !important;
            text-align: left !important;
        }
        .table-modern tbody td:first-child {
            padding-top: 0 !important;
        }
        .table-modern tbody td:last-child {
            border-bottom: none !important;
            padding-bottom: 0 !important;
        }

        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            margin-bottom: 6px; /* Memberi jarak ke datanya */
            display: block;
            width: 100%;
        }
    }
</style>

<script>
   $(document).ready(function() {
        $('#dataTables-example').DataTable({
            pageLength: 25,
            autoWidth: false,
            responsive: false,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            language: {
                search: "Cari Data:",
                searchPlaceholder: "Ketik pencarian...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            }
        });
        
        $('.dataTables_filter').addClass('mb-3');
        $('.dataTables_length').addClass('mb-3');
    });
</script>