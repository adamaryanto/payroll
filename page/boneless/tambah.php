<?php
$simpan = @$_POST['simpan'];
if ($simpan) {
    $tgl = @$_POST['tgl'];
    $jumlah_mobil = @$_POST['jumlah_mobil'];
    $keterangan = @$_POST['keterangan'];

    // Insert Header
    $sql_header = $koneksi->query("INSERT INTO tb_boneless (tgl, jumlah_mobil, keterangan, tgl_updt) VALUES ('$tgl', '$jumlah_mobil', '$keterangan', NOW())");
    $id_header = $koneksi->insert_id;

    if ($id_header) {
        // Insert Details
        $nama_items = $_POST['nama_item'];
        $qtys = $_POST['qty'];
        $hargas = $_POST['harga'];
        $totals = $_POST['total'];

        for ($i = 0; $i < count($nama_items); $i++) {
            $item = $nama_items[$i];
            $qty = $qtys[$i];
            $hrg = $hargas[$i];
            $ttl = $totals[$i];

            if ($qty > 0 || $ttl > 0) {
                $koneksi->query("INSERT INTO tb_boneless_detail (id_boneless, nama_item, qty, harga, total) VALUES ('$id_header', '$item', '$qty', '$hrg', '$ttl')");
            }
        }

        ?>
        <script type="text/javascript">
            alert("Data Berhasil Disimpan");
            window.location.href = "?page=boneless";
        </script>
        <?php
    }
}

$default_items = [
    "BORAS" => 800,
    "BONLESS KEVIN" => 700,
    "PAHA SP HOKA" => 500,
    "SHILIN" => 500,
    "CIMORY" => 500,
    "PAHA SP SHIHLIN" => 500,
    "TOM TOM" => 500
];
?>

<div class="container-fluid px-2 mt-4 mb-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">
                <div class="border-b border-gray-100 py-4 px-5 bg-white flex justify-between items-center">
                    <h3 class="text-xl font-bold m-0" style="color: #2563eb;"><i class="fas fa-plus mr-2"></i>Tambah Data Boneless</h3>
                    <div class="text-sm text-gray-500 font-medium">Tanggal: <?= date('d-m-Y') ?></div>
                </div>

                <div class="p-5">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label class="text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Produksi</label>
                                <input type="date" name="tgl" class="form-control" required value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="text-xs font-bold text-gray-700 uppercase mb-1">Jumlah Mobil (Potong)</label>
                                <input type="number" name="jumlah_mobil" class="form-control" placeholder="0" required>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="text-xs font-bold text-gray-700 uppercase mb-3 block">Rincian Item Boneless</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover shadow-sm" id="itemTable">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="py-2 text-center" style="width: 50px;">#</th>
                                            <th class="py-2">Nama Item</th>
                                            <th class="py-2 text-center" style="width: 150px;">QTY / Weight</th>
                                            <th class="py-2 text-center" style="width: 150px;">Harga Satuan</th>
                                            <th class="py-2 text-center" style="width: 180px;">Total</th>
                                            <th class="py-2 text-center" style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($default_items as $name => $price): ?>
                                        <tr class="item-row">
                                            <td class="text-center align-middle font-bold text-gray-400"><?= $no++ ?></td>
                                            <td>
                                                <input type="text" name="nama_item[]" class="form-control" value="<?= $name ?>" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="qty[]" class="form-control text-center qty-input" placeholder="0.00">
                                            </td>
                                            <td>
                                                <input type="number" name="harga[]" class="form-control text-center harga-input" value="<?= $price ?>" required>
                                            </td>
                                            <td>
                                                <input type="number" name="total[]" class="form-control text-right font-bold text-blue-600 total-row-input" readonly value="0">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-xs btn-outline-danger remove-row"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-50 font-bold">
                                            <td colspan="4" class="text-right py-3 uppercase text-xs tracking-wider">Grand Total Boneless</td>
                                            <td class="text-right py-3 text-lg text-indigo-600" id="grandTotalDisplay">Rp 0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <button type="button" id="addRow" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris
                            </button>
                        </div>

                        <div class="form-group mt-6">
                            <label class="text-xs font-bold text-gray-700 uppercase mb-1">Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Tambahkan catatan jika ada..."></textarea>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="submit" name="simpan" value="simpan" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition-colors shadow-md">
                                <i class="fas fa-save mr-2"></i> Simpan Laporan
                            </button>
                            <a href="?page=boneless" class="px-8 py-3 bg-gray-100 text-gray-600 rounded-lg font-bold hover:bg-gray-200 transition-colors">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#itemTable tbody');
        const addRowBtn = document.getElementById('addRow');
        const grandTotalDisplay = document.getElementById('grandTotalDisplay');

        function calculate() {
            let grandTotal = 0;
            const rows = document.querySelectorAll('.item-row');
            
            rows.forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.harga-input').value) || 0;
                const total = Math.round(qty * price);
                
                row.querySelector('.total-row-input').value = total;
                grandTotal += total;
            });

            grandTotalDisplay.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        tableBody.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty-input') || e.target.classList.contains('harga-input')) {
                calculate();
            }
        });

        addRowBtn.addEventListener('click', function() {
            const rowCount = tableBody.querySelectorAll('tr').length + 1;
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            newRow.innerHTML = `
                <td class="text-center align-middle font-bold text-gray-400">${rowCount}</td>
                <td><input type="text" name="nama_item[]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="qty[]" class="form-control text-center qty-input" placeholder="0.00"></td>
                <td><input type="number" name="harga[]" class="form-control text-center harga-input" required></td>
                <td><input type="number" name="total[]" class="form-control text-right font-bold text-blue-600 total-row-input" readonly value="0"></td>
                <td class="text-center align-middle"><button type="button" class="btn btn-xs btn-outline-danger remove-row"><i class="fas fa-times"></i></button></td>
            `;
            tableBody.appendChild(newRow);
        });

        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                const rows = tableBody.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                    calculate();
                    
                    // Re-index remaining rows
                    document.querySelectorAll('.item-row').forEach((row, index) => {
                        row.cells[0].innerText = index + 1;
                    });
                } else {
                    alert('Minimal harus ada 1 baris.');
                }
            }
        });

        // Init
        calculate();
    });
</script>
