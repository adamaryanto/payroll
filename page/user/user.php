<style>
    /* Membersihkan layout setelah elemen mengambang (float) */
    .dataTables_wrapper::after {
        content: "";
        clear: both;
        display: table;
    }
    
    /* --- Area Atas: Show Entries (Kiri) & Search (Kanan) --- */
    .dataTables_wrapper .dataTables_length {
        float: left;
        margin-bottom: 1rem;
        color: #4b5563; /* text-gray-600 */
    }
    .dataTables_wrapper .dataTables_filter {
        float: right;
        margin-bottom: 1rem;
        color: #4b5563;
    }
    /* Styling input search & dropdown length */
    .dataTables_wrapper .dataTables_filter input, 
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d1d5db; /* border-gray-300 */
        border-radius: 0.375rem;   /* rounded-md */
        padding: 0.25rem 0.5rem;
        margin-left: 0.5rem;
        outline: none;
        background-color: #fff;
    }
    .dataTables_wrapper .dataTables_filter input:focus, 
    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #3b82f6; /* blue-500 */
        box-shadow: 0 0 0 1px #3b82f6;
    }

    /* --- Area Bawah: Info (Kiri) & Pagination (Kanan) --- */
    .dataTables_wrapper .dataTables_info {
        float: left;
        margin-top: 1rem;
        color: #6b7280; /* text-gray-500 */
        font-size: 0.875rem; /* text-sm */
    }
    .dataTables_wrapper .dataTables_paginate {
        float: right;
        margin-top: 1rem;
    }
    
    /* Styling Tombol Pagination (Previous, 1, 2, Next) */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin-left: 0.25rem;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        background-color: #ffffff;
        color: #374151 !important; /* text-gray-700 */
        font-size: 0.875rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled):not(.current) {
        background-color: #f3f4f6; /* bg-gray-100 */
        color: #111827 !important;
    }
    
    /* Tombol Aktif (Current Page) */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background-color: #2563eb !important; /* bg-blue-600 */
        color: #ffffff !important;
        border-color: #2563eb;
    }
    
    /* Tombol Non-aktif (Disabled - spt Previous di halaman 1) */
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #f9fafb !important;
    }
</style>

<div class="row px-3 mt-4">
    <div class="col-md-12">
        <div class="card rounded-2xl shadow-sm border-0">
            <div class="card-header bg-white border-b border-gray-100 py-4 flex justify-between items-center rounded-t-2xl">
                <h3 class="card-title text-xl font-bold text-gray-800 m-0">Data Akun User</h3>
                <div class="card-tools">
                    <a href="?page=user&aksi=tambah" class="btn btn-primary bg-brand-600 hover:bg-brand-700 border-0 rounded-lg shadow-sm px-4 py-2 font-medium transition-colors">
                        <i class="fas fa-user-plus mr-2"></i> Tambah User
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped border-b border-gray-100" id="dataTables-example">
                        <thead class="bg-gray-50 text-gray-600 text-sm">
                                        <tr>
                                        <th width="5%">No</th>
                                         <th >Username</th>
                                         <th class="text-center" width="10%">Role</th>
                                         <th class="text-center" width="10%">Aksi</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php


$no = 1;


$tampil = $koneksi->query("SELECT * FROM ms_login");
    while ($data=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $data['user_login'] ?></td>
<td><?php echo $data['role'] ?></td>
<td class="text-center align-middle">
    <div class="flex flex-wrap gap-2 justify-center">
        <a href="?page=user&aksi=ubah&id=<?php echo $data['id_login'];?>" class="btn btn-sm bg-amber-500 hover:bg-amber-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Ubah User"><i class="fas fa-edit"></i></a>
        <a href="?page=user&aksi=hapus&id=<?php echo $data['id_login'];?>" class="btn btn-sm bg-rose-500 hover:bg-rose-600 text-white border-0 shadow-sm rounded-md transition-colors" title="Hapus User"><i class="fas fa-trash"></i></a>
    </div>
</td>
</tr>

                                       <?php  $no++; } ?>

                                    </tbody>   
                                    </table>
                            </div>
                        </div></div></div>
        </div>
    </div>
  
    <script>
 $(document).ready( function () {
$('#dataTables-example').DataTable({
    pageLength: 100,
    "searching": true
}
);

} );
</script>