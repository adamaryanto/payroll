<div class="container-fluid px-4 mt-5 mb-5">
    <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
        
        <div class="card-header bg-white border-b border-gray-100 py-5 px-6 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-extrabold text-gray-800 tracking-tight m-0">Data Karyawan (SIAC)</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola data sakit, ijin, alfa, dan cuti karyawan</p>
            </div>
            <div class="card-tools">
                <a href="?page=karyawan&aksi=tambah" class="inline-flex items-center bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-6 rounded-lg transition duration-200 ease-in-out transform hover:-translate-y-0.5 shadow-md text-base">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tambah Karyawan
                </a>
            </div>
        </div>

        <div class="card-body p-0"> 
            <div class="table-responsive p-4"> 
                <table class="table table-hover align-middle mb-0" id="dataTables-example" style="width:100%">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-4 text-sm font-bold text-gray-700 uppercase tracking-wider text-center">No</th>
                            <th class="px-4 py-4 text-sm font-bold text-gray-700 uppercase tracking-wider">No. Absen</th>
                            <th class="px-4 py-4 text-sm font-bold text-gray-700 uppercase tracking-wider">Nama Karyawan</th>
                            <th class="px-4 py-4 text-sm font-bold text-gray-700 uppercase tracking-wider text-center">L/P</th>
                            <th class="px-4 py-4 text-sm font-bold text-gray-700 uppercase tracking-wider">Alamat Tinggal</th>
                            <th class="px-4 py-4 text-sm font-bold text-gray-700 uppercase tracking-wider text-center">Status</th>
                            <th class="px-4 py-4 text-sm font-bold text-gray-700 uppercase tracking-wider text-center" style="min-width: 250px;">Aksi / Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <?php
                        $no = 1;
                        $tampil = $koneksi->query("SELECT * FROM ms_karyawan");
                        while ($datakaryawan = $tampil->fetch_assoc()) {
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td data-label="No" class="text-center text-lg text-gray-700 font-medium"><?php echo $no++; ?></td>
                            <td data-label="No. Absen" class="text-lg font-bold text-gray-900"><?php echo $datakaryawan['no_absen']; ?></td>
                            <td data-label="Nama Karyawan" class="text-xl font-black text-gray-900 uppercase tracking-tight"><?php echo $datakaryawan['nama_karyawan']; ?></td>
                            <td data-label="L/P" class="text-center text-lg text-gray-700"><?php echo $datakaryawan['jenis_kelamin']; ?></td>
                            <td data-label="Alamat Tinggal" class="text-lg text-gray-700"><?php echo $datakaryawan['alamat_tinggal']; ?></td>
                            <td data-label="Status" class="text-center">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-md border border-gray-300 text-gray-700">
                                    <?php echo $datakaryawan['status_karyawan']; ?>
                                </span>
                            </td>
                            <td data-label="Aksi / Detail" class="text-center">
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <a href="?page=siac&aksi=sakit&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                       class="flex-1 px-4 py-2.5 bg-white text-gray-800 hover:bg-gray-100 rounded-md text-sm font-bold transition-all border border-gray-300 text-center">Sakit</a>
                                    <a href="?page=siac&aksi=ijin&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                       class="flex-1 px-4 py-2.5 bg-white text-gray-800 hover:bg-gray-100 rounded-md text-sm font-bold transition-all border border-gray-300 text-center">Ijin</a>
                                    <a href="?page=siac&aksi=alfa&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                       class="flex-1 px-4 py-2.5 bg-white text-gray-800 hover:bg-gray-100 rounded-md text-sm font-bold transition-all border border-gray-300 text-center">Alfa</a>
                                    <a href="?page=siac&aksi=cuti&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                       class="flex-1 px-4 py-2.5 bg-white text-gray-800 hover:bg-gray-100 rounded-md text-sm font-bold transition-all border border-gray-300 text-center">Cuti</a>
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
        margin-bottom: 1.5rem !important;
        font-size: 1rem;
        color: #6b7280;
        float: left;
    }

    /* RESPONSIVE TABLE "STACKED" VIEW */
    @media screen and (max-width: 768px) {
        /* Sembunyikan Header di Mobile */
        #dataTables-example thead {
            display: none;
        }

        /* Transform Table Row jadi "Card" */
        #dataTables-example tbody tr {
            display: block;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            background: #fff;
        }

        /* Transform Table Cell jadi Block */
        #dataTables-example tbody td {
            display: flex;
            justify-content: space-between;
            text-align: right !important;
            padding: 0.75rem 0.5rem !important;
            border: none !important;
            border-bottom: 1px solid #f3f4f6 !important;
            width: 100% !important;
        }

        #dataTables-example tbody td:last-child {
            border-bottom: none !important;
            display: block;
            text-align: center !important;
        }

        /* Munculkan Label dari data-label attribute */
        #dataTables-example tbody td:before {
            content: attr(data-label);
            float: left;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #6b7280;
            padding-right: 1rem;
            line-height: 2;
        }

        /* Action buttons agar rapi di mobile */
        #dataTables-example tbody td div.flex {
            margin-top: 0.5rem;
            flex-direction: row;
            flex-wrap: wrap;
        }
        #dataTables-example tbody td div.flex a {
            flex: 1 1 45%; /* 2 kolom per baris di mobile */
        }
    }
</style>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            pageLength: 10,
            responsive: true,
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
        
        $('.dataTables_filter').addClass('mb-3');
    });
</script>