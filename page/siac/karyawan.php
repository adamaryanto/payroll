<div class="container mx-auto py-8 px-4">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        
        <div class="bg-slate-800 px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-white tracking-wide">
                Data Karyawan
            </h3>
            <a href="?page=karyawan&aksi=tambah" 
               class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg transition-colors duration-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Data
            </a>
        </div>

        <div class="p-4">
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200" id="dataTables-example">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Absen</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">L/P</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Alamat Tinggal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider" colspan="4">Aksi / Detail</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php
                        $no = 1;
                        $tampil = $koneksi->query("SELECT * FROM ms_karyawan");
                        while ($datakaryawan = $tampil->fetch_assoc()) {
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo $no; ?></td>
                            <td class="px-4 py-3 text-sm font-mono text-indigo-600 font-medium"><?php echo $datakaryawan['no_absen']; ?></td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800"><?php echo $datakaryawan['nama_karyawan']; ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo $datakaryawan['jenis_kelamin']; ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600 truncate max-w-xs"><?php echo $datakaryawan['alamat_tinggal']; ?></td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    <?php echo $datakaryawan['status_karyawan']; ?>
                                </span>
                            </td>
                            <td class="px-1 py-3 text-center">
                                <a href="?page=siac&aksi=sakit&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                   class="px-3 py-1 bg-green-100 text-green-700 hover:bg-green-200 rounded-md text-xs font-bold transition-all">Sakit</a>
                            </td>
                            <td class="px-1 py-3 text-center">
                                <a href="?page=siac&aksi=ijin&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                   class="px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-md text-xs font-bold transition-all">Ijin</a>
                            </td>
                            <td class="px-1 py-3 text-center">
                                <a href="?page=siac&aksi=alfa&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                   class="px-3 py-1 bg-orange-100 text-orange-700 hover:bg-orange-200 rounded-md text-xs font-bold transition-all">Alfa</a>
                            </td>
                            <td class="px-1 py-3 text-center">
                                <a href="?page=siac&aksi=cuti&id=<?php echo $datakaryawan['id_karyawan'];?>" 
                                   class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded-md text-xs font-bold transition-all">Cuti</a>
                            </td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataTables-example').DataTable({
        scrollY: "400px",
        scrollX: true,
        scrollCollapse: true,
        pageLength: 25, // Diubah dari 1000 agar tidak lag di browser
        fixedHeader: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari data karyawan..."
        }
    });
    
    // Custom styling untuk input pencarian DataTables agar sesuai Tailwind
    $('.dataTables_filter input').addClass('border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-indigo-500 outline-none ml-2');
});
</script>