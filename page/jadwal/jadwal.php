<?php
// jadwal.php - Refined Layout & Column Swap

$tampil = $koneksi->query("SELECT * from tb_jadwal");
?>

<style>
    /* Styling Card & Header Consistent with tambah.php */
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

    /* Modern Button */
    .btn-modern {
        background-color: #5F9EA0;
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
        background-color: #4a8082;
        color: white;
        box-shadow: 0 4px 8px rgba(95, 158, 160, 0.2);
    }

    /* Table Styling */
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

    /* Action Buttons in Cell */
    .action-links {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
        text-decoration: none !important;
    }

    .btn-edit { background-color: #fef3c7; color: #d97706; }
    .btn-edit:hover { background-color: #fde68a; }
    
    .btn-delete { background-color: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background-color: #fecaca; }

    /* --- STYLING KHUSUS DATATABLES INFO & PAGINATION --- */
    
    /* Membungkus info dan pagination di bawah tabel */
    .dataTables_wrapper {
        position: relative;
    }

    /* Styling Teks Info ("Showing 1 to 10...") */
    .dataTables_wrapper .dataTables_info {
        padding-top: 20px !important;
        color: #64748b !important;
        font-size: 13px !important;
        float: left; /* Dorong ke kiri */
    }

    /* Container Pagination */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 15px !important;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 4px;
        float: right; /* Dorong ke kanan */
    }

    /* Tombol Prev/Next & Angka */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #e2e8f0 !important;
        background: white !important;
        border-radius: 6px !important;
        padding: 5px 12px !important; 
        color: #475569 !important;
        font-weight: 500 !important;
        font-size: 13px;
        cursor: pointer;
        margin: 0 !important;
        transition: all 0.2s;
    }
    
    /* Efek Hover Tombol Pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8fafc !important;
        color: #5F9EA0 !important;
        border-color: #cbd5e1 !important;
    }

    /* Tombol Aktif (Angka halaman saat ini) */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #5F9EA0 !important;
        border-color: #5F9EA0 !important;
        color: white !important;
        box-shadow: 0 2px 4px rgba(95, 158, 160, 0.2);
    }
    
    /* Tombol Disabled (Prev/Next mentok) */
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        cursor: not-allowed;
        border-color: #e2e8f0 !important;
        box-shadow: none;
    }
    
    /* Clearfix agar tidak menabrak elemen lain */
    .dataTables_wrapper::after {
        content: "";
        display: table;
        clear: both;
    }

    /* RESPONSIVE TABLE "STACKED" VIEW (Mobile View) */
    @media screen and (max-width: 768px) {
        .table-responsive { 
            padding: 12px !important;
            overflow-x: visible !important;
        }
        .card-modern-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 16px;
        }
        .table-modern thead { display: none !important; }
        .table-modern tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .table-modern tbody td {
            display: flex;
            align-items: flex-start;
            text-align: left !important;
            padding: 8px 10px !important;
            border: none !important;
            border-bottom: 1px solid #f3f4f6 !important;
            width: 100% !important;
            font-size: 13px;
        }
        .table-modern tbody td:last-child { 
            border-bottom: none !important; 
            padding-top: 12px !important; 
            justify-content: flex-start !important; 
        }
        .table-modern tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            text-align: left;
            flex-basis: 35%;
            min-width: 120px;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        /* Merapikan Info & Pagination di layar kecil */
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            float: none;
            text-align: center;
            justify-content: center;
            width: 100%;
            margin-top: 10px;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card-modern">
                <div class="card-modern-header">
                    <h3 class="card-modern-title">
                        <i class="fas fa-calendar-alt" style="color:#5F9EA0;"></i> Data Jadwal Shift
                    </h3>
                    <a href="?page=jadwal&aksi=tambah" class="btn-modern">
                        <i class="fas fa-plus"></i> Tambah Jadwal
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-modern w-full" id="dataTables-example">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Shift</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Istirahat Keluar</th>
                                <th>Istirahat Masuk</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = $tampil->fetch_assoc()) :
                            ?>
                            <tr>
                                <td data-label="No"><?php echo $no ?></td>
                                <td data-label="Shift"><strong><?php echo $data['keterangan'] ?></strong></td>
                                <td data-label="Jam Masuk"><?php echo $data['jam_masuk'] ?></td>
                                <td data-label="Jam Keluar"><?php echo $data['jam_keluar'] ?></td>
                                <td data-label="Istirahat Keluar"><?php echo $data['istirahat_keluar'] ?></td>
                                <td data-label="Istirahat Masuk"><?php echo $data['istirahat_masuk'] ?></td>
                                <td data-label="Aksi">
                                    <div class="action-links">
                                        <a href="?page=jadwal&aksi=ubah&id=<?php echo $data['id_jadwal'];?>" class="btn-action btn-edit" title="Ubah Jadwal">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?page=jadwal&aksi=hapus&id=<?php echo $data['id_jadwal'];?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" class="btn-action btn-delete" title="Hapus Jadwal">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php $no++; endwhile; ?>
                        </tbody>   
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var isMobile = window.innerWidth <= 768;
    
    $('#dataTables-example').DataTable({
        pageLength: 25,
        responsive: false,
        scrollX: !isMobile,
        autoWidth: !isMobile,
        language: {
            search: "",
            searchPlaceholder: "Cari jadwal...",
            lengthMenu: "Tampil _MENU_",
            info: "Menampilkan _START_ sd _END_ dari _TOTAL_ data",
            infoEmpty: "Data tidak ditemukan",
            infoFiltered: "(disaring dari _MAX_ total data)",
            paginate: {
                previous: "Prev",
                next: "Next"
            }
        }
    });
    
    // Styling helper for search input
    $('.dataTables_filter input').addClass('form-control').css({
        'border-radius': '8px',
        'border': '1px solid #e0e6ed',
        'padding': '6px 12px',
        'margin-bottom': '15px'
    });
    
    if(!isMobile) {
        $('.dataTables_filter').css('float', 'right');
        $('.dataTables_length').css('float', 'left');
    }
});
</script>