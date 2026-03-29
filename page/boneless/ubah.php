<?php
$ref = $_GET['ref'] ?? 'boneless';
$view_param = isset($_GET['view']) ? '&view=1' : '';
$id = $_GET['id'];

// 1. Ambil Data Header
$sql_header = $koneksi->query("SELECT * FROM tb_boneless WHERE id_boneless = '$id'");
$header = $sql_header->fetch_assoc();

// 2. Ambil Biaya Mobil (Master)
$sql_master = $koneksi->query("SELECT biaya_mobil FROM tb_biayamobil LIMIT 1");
$row_master = $sql_master->fetch_assoc();
$harga_per_mobil = $row_master['biaya_mobil'] ?? 0;

// 3. Ambil Data Detail
$sql_detail = $koneksi->query("SELECT * FROM tb_boneless_detail WHERE id_boneless = '$id'");
$details = [];
while ($row = $sql_detail->fetch_assoc()) {
    $details[] = $row;
}

// 4. Proses Simpan Perubahan
$simpan = @$_POST['simpan'];
if ($simpan) {
    $id_rkk = @$_POST['id_rkk'];
    $tgl = @$_POST['tgl'];
    $jumlah_mobil = @$_POST['jumlah_mobil'];
    $keterangan = @$_POST['keterangan'];

    // Update Header
    $koneksi->query("UPDATE tb_boneless SET 
        id_rkk = '$id_rkk', 
        tgl = '$tgl', 
        jumlah_mobil = '$jumlah_mobil', 
        keterangan = '$keterangan' 
        WHERE id_boneless = '$id'");

    // Sync Details: Hapus yang lama, masukkan yang baru
    $koneksi->query("DELETE FROM tb_boneless_detail WHERE id_boneless = '$id'");

    $nama_items = $_POST['nama_item'];
    $qtys = $_POST['qty'];
    $hargas = $_POST['harga'];
    $totals = $_POST['total'];
    $types = $_POST['type'];

    if (!empty($nama_items)) {
        for ($i = 0; $i < count($nama_items); $i++) {
            $item = $koneksi->real_escape_string($nama_items[$i]);
            if ($item != "") {
                $qty = (float)($qtys[$i] ?: 0);
                $hrg = (float)($hargas[$i] ?: 0);
                $ttl = (float)($totals[$i] ?: 0);
                $type = $koneksi->real_escape_string($types[$i]); // plus atau minus

                $koneksi->query("INSERT INTO tb_boneless_detail (id_boneless, nama_item, qty, harga, total, jenis) 
                             VALUES ('$id', '$item', '$qty', '$hrg', '$ttl', '$type')");
            }
        }
    }

    echo '<!DOCTYPE html>
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Data Berhasil Diubah",
                confirmButtonColor: "#2563eb",
                confirmButtonText: "OK"
            }).then((result) => {
                window.location.href = "?page=boneless&ref=' . $ref . $view_param . '";
            });
        </script>
    </body>
    </html>';
    exit;
}
?>

<div class="container-fluid px-2 mt-4 mb-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">
                <div class="border-b border-gray-100 py-4 px-4 md:px-5 bg-white flex flex-col md:flex-row justify-between items-center gap-3">
                    <div class="flex items-center gap-3">
                        <a href="?page=boneless&ref=<?= $ref ?><?= $view_param ?>" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors border border-gray-200">
                            <i class="fas fa-arrow-left text-xs"></i>
                        </a>
                        <h3 class="text-xl font-bold m-0" style="color: #2563eb;"><i class="fas fa-edit mr-2"></i>Ubah Data Boneless</h3>
                    </div>
                    <div class="text-sm text-gray-500 font-medium bg-gray-50 px-3 py-1 rounded-md border border-gray-200">
                        ID: #<?= $id ?>
                    </div>
                </div>

                <div class="p-4 md:p-5">
                    <form method="POST">
                        <div class="row mb-2">
                            <div class="col-md-4 form-group mb-3 md:mb-0">
                                <label class="text-xs font-bold text-gray-700 uppercase mb-1">Data Rencana (Locked)</label>
                                <?php
                                $id_rkk_saved = $header['id_rkk'];
                                $sql_rkk_fixed = $koneksi->query("SELECT * FROM tb_rkk WHERE id_rkk = '$id_rkk_saved'");
                                $row_fixed = $sql_rkk_fixed->fetch_assoc();
                                $display_rencana = date('d-m-Y', strtotime($row_fixed['tgl_rkk'])) . ' - ' . $row_fixed['keterangan'];
                                ?>
                                <div class="h-[42px] flex items-center px-3 bg-gray-100 border border-gray-200 rounded-lg font-medium text-gray-600 shadow-sm overflow-hidden whitespace-nowrap text-sm">
                                    <i class="fas fa-lock mr-2 text-gray-400 text-xs"></i>
                                    <?= $display_rencana ?>
                                </div>
                                <input type="hidden" name="id_rkk" value="<?= $id_rkk_saved ?>">
                                <input type="hidden" name="tgl" value="<?= $header['tgl'] ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="text-xs font-bold text-gray-700 uppercase mb-1">Jumlah Mobil (Potong)</label>
                                <input type="number" name="jumlah_mobil" class="form-control h-[42px]" placeholder="0" value="<?= $header['jumlah_mobil'] ?>" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="text-xs font-bold text-gray-700 uppercase mb-1">Biaya / Mobil (Master)</label>
                                <div class="h-[42px] flex items-center px-3 bg-blue-50 border border-blue-200 rounded-lg font-bold text-blue-600 shadow-sm" id="staticMasterDisplay">
                                    Rp <?= number_format($harga_per_mobil, 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 md:mt-6">
                            <label class="text-xs font-bold text-green-700 uppercase mb-3 block"><i class="fas fa-plus-circle mr-1"></i> Rincian Item Penambah (Plus)</label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablePlus">
                                    <thead class="bg-green-50">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">#</th>
                                            <th>Nama Item</th>
                                            <th style="width: 120px;">QTY</th>
                                            <th style="width: 150px;">Harga</th>
                                            <th style="width: 180px;">Total</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no_p = 1;
                                        foreach ($details as $row): if ($row['jenis'] == 'plus'): ?>
                                                <tr class="item-row row-plus">
                                                    <td class="text-center font-bold text-gray-400"><?= $no_p++ ?></td>
                                                    <td>
                                                        <input type="text" name="nama_item[]" class="form-control" value="<?= $row['nama_item'] ?>" required>
                                                        <input type="hidden" name="type[]" value="plus">
                                                    </td>
                                                    <td><input type="number" step="any" name="qty[]" class="form-control text-center qty-input" value="<?= $row['qty'] == 0 ? '' : (float)$row['qty'] ?>"></td>
                                                    <td><input type="number" name="harga[]" class="form-control text-center harga-input" value="<?= $row['harga'] ?>"></td>
                                                    <td><input type="number" name="total[]" class="form-control text-right font-bold text-green-600 bg-green-50 total-row-input" readonly value="<?= $row['total'] ?>"></td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-times"></i></button></td>
                                                </tr>
                                        <?php endif;
                                        endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="button" id="addRowPlus" class="btn btn-sm btn-outline-success font-bold"><i class="fas fa-plus mr-1"></i> Tambah Item Plus</button>
                            </div>
                        </div>

                        <hr class="my-5 border-gray-200">

                        <div class="mt-4">
                            <label class="text-xs font-bold text-red-700 uppercase mb-3 block"><i class="fas fa-minus-circle mr-1"></i> Rincian Item Pengurang (Minus)</label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tableMinus">
                                    <thead class="bg-red-50">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">#</th>
                                            <th>Nama Item</th>
                                            <th style="width: 120px;">QTY</th>
                                            <th style="width: 150px;">Harga</th>
                                            <th style="width: 180px;">Total</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no_m = 1;
                                        foreach ($details as $row): if ($row['jenis'] == 'minus'): ?>
                                                <tr class="item-row row-minus">
                                                    <td class="text-center font-bold text-gray-400"><?= $no_m++ ?></td>
                                                    <td>
                                                        <input type="text" name="nama_item[]" class="form-control" value="<?= $row['nama_item'] ?>" required>
                                                        <input type="hidden" name="type[]" value="minus">
                                                    </td>
                                                    <td><input type="number" step="any" name="qty[]" class="form-control text-center qty-input" value="<?= $row['qty'] == 0 ? '' : (float)$row['qty'] ?>"></td>
                                                    <td><input type="number" name="harga[]" class="form-control text-center harga-input" value="<?= $row['harga'] ?>"></td>
                                                    <td><input type="number" name="total[]" class="form-control text-right font-bold text-red-600 bg-red-50 total-row-input" readonly value="<?= $row['total'] ?>"></td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-times"></i></button></td>
                                                </tr>
                                        <?php endif;
                                        endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="button" id="addRowMinus" class="btn btn-sm btn-outline-danger font-bold"><i class="fas fa-plus mr-1"></i> Tambah Item Minus</button>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <h4 class="text-sm font-bold text-gray-700 uppercase mb-3"><i class="fas fa-calculator mr-2"></i>Ringkasan Biaya</h4>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-lg font-extrabold text-gray-800 uppercase">Grand Total Akhir</span>
                                <span class="text-xl font-black text-indigo-700" id="summaryGrandTotal">Rp 0</span>
                            </div>
                        </div>

                        <div class="form-group mt-6">
                            <label class="text-xs font-bold text-gray-700 uppercase mb-1">Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="3"><?= $header['keterangan'] ?></textarea>
                        </div>

                        <div class="mt-8 flex flex-col md:flex-row gap-3">
                            <button type="submit" name="simpan" value="simpan" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-md">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                            <a href="?page=boneless&ref=<?= $ref ?><?= $view_param ?>" class="px-8 py-3 bg-gray-100 text-gray-600 rounded-lg font-bold text-center border">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jmlMobilInput = document.querySelector('input[name="jumlah_mobil"]');
        const summaryGrandTotal = document.getElementById('summaryGrandTotal');
        const masterText = document.getElementById('staticMasterDisplay').innerText;
        const masterCost = parseFloat(masterText.replace(/[^0-9]/g, '')) || 0;

        function calculate() {
            let totalItems = 0;
            const allRows = document.querySelectorAll('.item-row');

            allRows.forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                let price = parseFloat(row.querySelector('.harga-input').value) || 0;

                // Jika di tabel minus, pastikan harga dianggap negatif untuk kalkulasi
                if (row.classList.contains('row-minus') && price > 0) {
                    price = -Math.abs(price);
                } else if (row.classList.contains('row-plus')) {
                    price = Math.abs(price);
                }

                let totalRow = Math.round(qty * price);
                row.querySelector('.total-row-input').value = totalRow;
                totalItems += totalRow;
            });

            const jmlMobil = parseFloat(jmlMobilInput.value) || 0;
            const totalBiayaMobil = jmlMobil * masterCost;
            const grandTotal = totalItems + totalBiayaMobil;

            summaryGrandTotal.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        // Event Delegation untuk Input
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty-input') || e.target.classList.contains('harga-input') || e.target === jmlMobilInput) {
                calculate();
            }
        });

        // Hapus Baris
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
                calculate();
            }
        });

        // Tambah Baris
        function addNewRow(tableId, rowClass, colorClass, typeValue) {
            const tbody = document.querySelector(`#${tableId} tbody`);
            const tr = document.createElement('tr');
            tr.className = `item-row ${rowClass}`;
            tr.innerHTML = `
        <td class="text-center font-bold text-gray-400">-</td>
        <td>
            <input type="text" name="nama_item[]" class="form-control" required>
            <input type="hidden" name="type[]" value="${typeValue}">
        </td>
        <td><input type="number" step="any" name="qty[]" class="form-control text-center qty-input" value=""></td>
        <td><input type="number" name="harga[]" class="form-control text-center harga-input" value="0"></td>
        <td><input type="number" name="total[]" class="form-control text-right font-bold ${colorClass} total-row-input" readonly value="0"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-times"></i></button></td>
    `;
            tbody.appendChild(tr);
        }

        // Update pemanggilan tombolnya
        document.getElementById('addRowPlus').addEventListener('click', () => addNewRow('tablePlus', 'row-plus', 'text-green-600 bg-green-50', 'plus'));
        document.getElementById('addRowMinus').addEventListener('click', () => addNewRow('tableMinus', 'row-minus', 'text-red-600 bg-red-50', 'minus'));
        calculate(); // Jalankan kalkulasi awal
    });
</script>