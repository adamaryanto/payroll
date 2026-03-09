<?php
$tampil = $koneksi->query("SELECT A.*, (SELECT SUM(total) FROM tb_boneless_detail WHERE id_boneless = A.id_boneless) as grand_total FROM tb_boneless A ORDER BY tgl DESC");
?>

<div class="container-fluid px-2 mt-4 mb-4">
    <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">
        <div class="border-b border-gray-100 py-4 px-5 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-xl font-bold m-0" style="color: #2563eb;"><i class="fas fa-truck-loading mr-2"></i>Data Boneless</h3>
            </div>
            <div>
                <a href="?page=boneless&aksi=tambah" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white text-[15px] font-medium py-2 px-4 rounded shadow-sm transition-colors">
                    <i class="fas fa-plus mr-1.5"></i> Tambah Data
                </a>
            </div>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="w-full text-left border-collapse" id="dataTables-example">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-3 px-4 text-xs font-bold text-gray-700 uppercase">No</th>
                            <th class="py-3 px-4 text-xs font-bold text-gray-700 uppercase text-center">Tanggal</th>
                            <th class="py-3 px-4 text-xs font-bold text-gray-700 uppercase text-center">Jumlah Mobil</th>
                            <th class="py-3 px-4 text-xs font-bold text-gray-700 uppercase text-right">Total Biaya Boneless</th>
                            <th class="py-3 px-4 text-xs font-bold text-gray-700 uppercase">Keterangan</th>
                            <th class="py-3 px-4 text-xs font-bold text-gray-700 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $no = 1;
                        while ($data = $tampil->fetch_assoc()) :
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 text-sm text-gray-700"><?= $no++ ?></td>
                                <td class="py-3 px-4 text-sm text-gray-900 font-bold text-center"><?= date('d-m-Y', strtotime($data['tgl'])) ?></td>
                                <td class="py-3 px-4 text-sm text-gray-900 text-center font-bold">
                                   <span class="bg-amber-50 text-amber-700 px-3 py-1 rounded-full border border-amber-100">
                                       <?= $data['jumlah_mobil'] ?> Unit
                                   </span>
                                </td>
                                <td class="py-3 px-4 text-sm font-bold text-blue-600 text-right">Rp <?= number_format($data['grand_total'], 0, ',', '.') ?></td>
                                <td class="py-3 px-4 text-sm text-gray-600 italic"><?= htmlspecialchars($data['keterangan']) ?: '-' ?></td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="?page=boneless&aksi=ubah&id=<?= $data['id_boneless'] ?>" class="p-1 px-3 text-blue-600 bg-blue-50 border border-blue-100 rounded hover:bg-blue-600 hover:text-white transition-all text-xs font-bold">
                                            <i class="fas fa-edit mr-1"></i> Lihat / Edit
                                        </a>
                                        <a href="?page=boneless&aksi=hapus&id=<?= $data['id_boneless'] ?>" class="p-1 px-3 text-rose-600 bg-rose-50 border border-rose-100 rounded hover:bg-rose-600 hover:text-white transition-all text-xs font-bold" onclick="return confirm('Hapus data ini? Semua rincian item juga akan terhapus.')">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            pageLength: 25,
            autoWidth: false,
            responsive: true,
            language: {
                search: "Cari Data:",
                lengthMenu: "Tampil _MENU_",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data"
            }
        });
    });
</script>
