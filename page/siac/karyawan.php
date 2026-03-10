<div class="container-fluid px-2 sm:px-4 mt-4 sm:mt-5 mb-5">
    <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
        
        <div class="card-header bg-white border-b border-gray-100 py-4 px-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl font-extrabold text-gray-800 tracking-tight m-0">Data Karyawan (SIAC)</h3>
                <p class="text-xs text-gray-500 mt-1">Kelola data sakit, ijin, alfa, dan cuti karyawan</p>
            </div>
            <div class="card-tools w-full sm:w-auto">
                <a href="?page=karyawan&aksi=tambah" class="w-full sm:w-auto inline-flex justify-center items-center bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-5 rounded-lg transition duration-200 ease-in-out transform hover:-translate-y-0.5 shadow-md text-sm">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tambah Karyawan
                </a>
            </div>
        </div>

        <div class="card-body p-0"> 
            <div class="table-responsive p-3 sm:p-4"> 
                <table class="table table-hover align-middle mb-0" id="dataTables-example" style="width:100%">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase tracking-wider text-center">No</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase tracking-wider">No. Absen</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Karyawan</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase tracking-wider text-center">L/P</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase tracking-wider">Alamat Tinggal</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase tracking-wider text-center">Status</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-700 uppercase tracking-wider text-center" style="min-width: 200px;">Aksi / Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <?php
                        $no = 1;
                        $tampil = $koneksi->query("SELECT * FROM ms_karyawan");
                        while ($datakaryawan = $tampil->fetch_assoc()) {
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td data-label="No" class="text-center text-sm text-gray-700 font-medium"><?php echo $no++; ?></td>
                            <td data-label="No. Absen" class="text-sm font-bold text-gray-900"><?php echo $datakaryawan['no_absen']; ?></td>
                            <td data-label="Nama Karyawan" class="text-base font-black text-gray-900 uppercase tracking-tight"><?php echo $datakaryawan['nama_karyawan']; ?></td>
                            <td data-label="L/P" class="text-center text-sm text-gray-700"><?php echo $datakaryawan['jenis_kelamin']; ?></td>
                            <td data-label="Alamat Tinggal" class="text-sm text-gray-700"><?php echo $datakaryawan['alamat_tinggal']; ?></td>
                            <td data-label="Status" class="text-center">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-md border border-gray-300 text-gray-700">
                                    <?php echo $datakaryawan['status_karyawan']; ?>
                                </span>
                            </td>
                            <td data-label="Aksi / Detail" class="text-center">
                                <div class="flex flex-wrap items-center justify-center gap-1.5">
                                    <a href="?page=siac&aksi=sakit&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                       class="flex-1 px-3 py-2 bg-white text-gray-800 hover:bg-gray-100 rounded-md text-xs font-bold transition-all border border-gray-300 text-center">Sakit</a>
                                    <a href="?page=siac&aksi=ijin&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                       class="flex-1 px-3 py-2 bg-white text-gray-800 hover:bg-gray-100 rounded-md text-xs font-bold transition-all border border-gray-300 text-center">Ijin</a>
                                    <a href="?page=siac&aksi=alfa&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                       class="flex-1 px-3 py-2 bg-white text-gray-800 hover:bg-gray-100 rounded-md text-xs font-bold transition-all border border-gray-300 text-center">Alfa</a>
                                    <a href="?page=siac&aksi=cuti&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                       class="flex-1 px-3 py-2 bg-white text-gray-800 hover:bg-gray-100 rounded-md text-xs font-bold transition-all border border-gray-300 text-center">Cuti</a>
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

    /* Form Filter & Length Menu Desktop */
    .dataTables_wrapper .dataTables_length select {
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        padding: 4px 8px;
        margin: 0 5px;
        font-size: 0.875rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px !important;
        border: 1px solid #e5e7eb !important;
        padding: 6px 10px !important;
        outline: none;
        transition: all 0.2s;
        font-size: 0.875rem;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* PAGINATION STYLING Desktop */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 4px;
        font-size: 0.875rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #e5e7eb !important;
        background: white !important;
        border-radius: 6px !important;
        padding: 4px 10px !important;
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

    .dataTables_wrapper .dataTables_info {
        padding-top: 1.2rem !important;
        margin-bottom: 1rem !important;
        font-size: 0.875rem;
        color: #6b7280;
        float: left;
    }

    /* RESPONSIVE TABLE & DATATABLES MOBILE VIEW */
    @media screen and (max-width: 768px) {
        /* Styling Search & Entries DataTables agar tidak meluber */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            text-align: left !important;
            float: none !important;
            margin-bottom: 1rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            width: 100% !important; /* Search bar full width di mobile */
            margin-left: 0 !important;
            display: block;
            margin-top: 0.5rem;
        }
        .dataTables_wrapper .dataTables_info {
            float: none !important;
            text-align: center !important;
            margin-bottom: 0.5rem !important;
        }
        .dataTables_wrapper .dataTables_paginate {
            justify-content: center !important;
            flex-wrap: wrap; /* Pagination turun ke baris baru jika panjang */
            margin-top: 0.5rem !important;
        }

        /* Sembunyikan Header di Mobile */
        #dataTables-example thead {
            display: none;
        }

        /* Transform Table Row jadi "Card" */
        #dataTables-example tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            background: #fff;
        }

        /* Transform Table Cell jadi Block */
        #dataTables-example tbody td {
            display: flex;
            justify-content: flex-start; /* Ubah ke start agar label punya ruang bernapas */
            align-items: flex-start; /* Antisipasi teks alamat yang panjang */
            text-align: right !important;
            padding: 0.6rem 0.25rem !important;
            border: none !important;
            border-bottom: 1px solid #f3f4f6 !important;
            width: 100% !important;
        }

        #dataTables-example tbody td:last-child {
            border-bottom: none !important;
            display: block;
            text-align: center !important;
        }

        /* Label data-label attribute */
        #dataTables-example tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            color: #6b7280;
            flex-shrink: 0;
            width: 40%; /* Lebar fix untuk label agar rapi */
            text-align: left;
            line-height: 1.5;
        }

        /* Isi konten menyesuaikan lebar sisa */
        #dataTables-example tbody td > * {
            flex-grow: 1;
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
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_",
                paginate: {
                    previous: "<i class='fas fa-chevron-left text-xs'></i> Prev",
                    next: "Next <i class='fas fa-chevron-right text-xs'></i>"
                }
            }
        });
        
        $('.dataTables_filter').addClass('mb-3');
    });
</script>