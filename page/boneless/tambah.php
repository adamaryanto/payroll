<?php
$ref = $_GET['ref'] ?? 'boneless';
$view_param = isset($_GET['view']) ? '&view=1' : '';
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

            if ($qty >= 0 || $ttl >= 0) {
                $koneksi->query("INSERT INTO tb_boneless_detail (id_boneless, nama_item, qty, harga, total) VALUES ('$id_header', '$item', '$qty', '$hrg', '$ttl')");
            }
        }

        ?>
        <script type="text/javascript">
            alert("Data Berhasil Disimpan");
            window.location.href = "?page=boneless&ref=<?= $ref ?><?= $view_param ?>";
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
                <div class="border-b border-gray-100 py-4 px-4 md:px-5 bg-white flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <a href="?page=boneless&ref=<?= $ref ?><?= $view_param ?>" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors border border-gray-200">
                            <i class="fas fa-arrow-left text-xs"></i>
                        </a>
                        <h3 class="text-xl font-bold m-0" style="color: #2563eb;"><i class="fas fa-plus mr-2"></i>Tambah Data Boneless</h3>
                    </div>
                    <div class="text-sm text-gray-500 font-medium bg-gray-50 px-3 py-1 rounded-md border border-gray-200 w-full md:w-auto mt-2 md:mt-0">Tanggal: <?= date('d-m-Y') ?></div>
                </div>

                <div class="p-4 md:p-5">
                    <form method="POST">
                        <div class="row mb-2">
                            <div class="col-md-4 form-group mb-3 md:mb-0">
                                <label class="text-xs font-bold text-gray-700 uppercase mb-1">Data Rencana</label>
                                <select name="tgl" class="form-control h-[42px]" required>
                                    <option value="">-- Pilih Rencana --</option>
                                    <?php
                                    $sql_rkk = $koneksi->query("SELECT * FROM tb_rkk WHERE status_rkk = '0' ORDER BY tgl_rkk DESC");
                                    while ($row_rkk = $sql_rkk->fetch_assoc()) {
                                        echo '<option value="' . $row_rkk['tgl_rkk'] . '">' . date('d-m-Y', strtotime($row_rkk['tgl_rkk'])) . ' - ' . $row_rkk['keterangan'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="text-xs font-bold text-gray-700 uppercase mb-1">Jumlah Mobil (Potong)</label>
                                <input type="number" name="jumlah_mobil" class="form-control h-[42px]" placeholder="0" required>
                            </div>
                        </div>

                        <div class="mt-4 md:mt-6">
                            <label class="text-xs font-bold text-gray-700 uppercase mb-3 block">Rincian Item Boneless</label>
                            <div class="table-responsive px-0 md:px-1">
                                <table class="table table-bordered table-hover shadow-sm table-form-mobile" id="itemTable">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="py-2 text-center" style="width: 50px;">#</th>
                                            <th class="py-2">Nama Item</th>
                                            <th class="py-2 text-center" style="width: 150px;">QTY / Weight</th>
                                            <th class="py-2 text-center" style="width: 150px;">Harga Satuan</th>
                                            <th class="py-2 text-center" style="width: 180px;">Total</th>
                                            <th class="py-2 text-center" style="width: 60px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($default_items as $name => $price): ?>
                                        <tr class="item-row">
                                            <td data-label="Baris Ke-" class="md:text-center align-middle font-bold text-gray-400 py-3 md:py-2"><?= $no++ ?></td>
                                            <td data-label="Nama Item" class="py-3 md:py-2">
                                                <input type="text" name="nama_item[]" class="form-control input-modern" value="<?= $name ?>" required>
                                            </td>
                                            <td data-label="QTY / Weight" class="py-3 md:py-2">
                                                <input type="number" step="0.01" name="qty[]" class="form-control text-center input-modern qty-input" placeholder="0.00">
                                            </td>
                                            <td data-label="Harga Satuan" class="py-3 md:py-2">
                                                <input type="number" name="harga[]" class="form-control text-center input-modern harga-input" value="<?= $price ?>" required>
                                            </td>
                                            <td data-label="Total" class="py-3 md:py-2">
                                                <input type="number" name="total[]" class="form-control text-right font-bold text-blue-600 bg-blue-50/50 input-modern total-row-input" readonly value="0">
                                            </td>
                                            <td data-label="Aksi" class="md:text-center align-middle py-3 md:py-2 border-t border-gray-100 md:border-t-0 mt-2 md:mt-0">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-row w-full md:w-auto py-2 md:py-1">
                                                    <i class="fas fa-times md:mr-0 mr-1"></i> <span class="md:hidden font-bold">Hapus Baris Ini</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-50 font-bold border-t-2 border-indigo-100">
                                            <td colspan="4" class="text-right py-4 uppercase text-xs md:text-sm tracking-wider text-gray-600">Grand Total Boneless</td>
                                            <td colspan="2" class="md:text-right text-center py-4 text-xl md:text-lg text-indigo-700 font-extrabold" id="grandTotalDisplay">Rp 0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <button type="button" id="addRow" class="btn btn-sm btn-outline-primary mt-3 md:mt-2 w-full md:w-auto py-2 md:py-1 font-bold">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris Baru
                            </button>
                        </div>

                        <div class="form-group mt-6">
                            <label class="text-xs font-bold text-gray-700 uppercase mb-1">Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan jika ada..."></textarea>
                        </div>

                        <div class="mt-8 flex flex-col md:flex-row gap-3">
                            <button type="submit" name="simpan" value="simpan" class="w-full md:w-auto px-8 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition-colors shadow-md text-center">
                                <i class="fas fa-save mr-2"></i> Simpan Laporan
                            </button>
                            <a href="?page=boneless&ref=<?= $ref ?><?= $view_param ?>" class="w-full md:w-auto px-8 py-3 bg-gray-100 text-gray-600 rounded-lg font-bold hover:bg-gray-200 transition-colors text-center border border-gray-200">
                                Batal / Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling agar input form terlihat rapi */
    .input-modern {
        height: 38px;
        border-radius: 6px;
    }
    
    /* =========================================
       KHUSUS TAMPILAN MOBILE: KARTU INPUT FORM
       ========================================= */
    @media screen and (max-width: 768px) {
        .table-responsive {
            border: none !important;
        }
        
        .table-form-mobile thead {
            display: none !important;
        }
        
        /* Merubah setiap tag TR menjadi Kotak Kartu */
        .table-form-mobile tbody tr {
            display: block;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            background: #f8fafc; /* Warna latar sedikit abu untuk membedakan per baris */
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .table-form-mobile tbody td {
            display: flex;
            flex-direction: column;
            align-items: flex-start !important;
            padding: 10px 0 !important;
            border: none !important;
        }

        /* Menampilkan Label Judul di atas input */
        .table-form-mobile tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
            width: 100%;
        }
        
        /* Agar input mengambil sisa lebar penuh di HP */
        .table-form-mobile tbody td input {
            width: 100% !important;
        }

        /* Tampilan Footer (Grand Total) di HP */
        .table-form-mobile tfoot tr {
            display: flex;
            flex-direction: column;
            background: transparent !important;
            border: none !important;
            padding: 0;
            margin-top: 10px;
        }
        .table-form-mobile tfoot td {
            display: block;
            border: none !important;
            width: 100%;
        }
        .table-form-mobile tfoot td:first-child {
            padding-bottom: 0 !important;
            text-align: center !important;
        }
    }
</style>

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
            const rowCount = tableBody.querySelectorAll('tr.item-row').length + 1;
            const newRow = document.createElement('tr');
            newRow.className = 'item-row hover:bg-gray-50 transition-colors';
            
            // PENTING: data-label sudah disisipkan ke script JS ini agar saat nambah baris di HP tidak berantakan
            newRow.innerHTML = `
                <td data-label="Baris Ke-" class="md:text-center align-middle font-bold text-gray-400 py-3 md:py-2">${rowCount}</td>
                <td data-label="Nama Item" class="py-3 md:py-2"><input type="text" name="nama_item[]" class="form-control input-modern" required></td>
                <td data-label="QTY / Weight" class="py-3 md:py-2"><input type="number" step="0.01" name="qty[]" class="form-control text-center input-modern qty-input" placeholder="0.00"></td>
                <td data-label="Harga Satuan" class="py-3 md:py-2"><input type="number" name="harga[]" class="form-control text-center input-modern harga-input" required></td>
                <td data-label="Total" class="py-3 md:py-2"><input type="number" name="total[]" class="form-control text-right font-bold text-blue-600 bg-blue-50/50 input-modern total-row-input" readonly value="0"></td>
                <td data-label="Aksi" class="md:text-center align-middle py-3 md:py-2 border-t border-gray-100 md:border-t-0 mt-2 md:mt-0">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row w-full md:w-auto py-2 md:py-1">
                        <i class="fas fa-times md:mr-0 mr-1"></i> <span class="md:hidden font-bold">Hapus Baris Ini</span>
                    </button>
                </td>
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
                        // Mengubah nilai text dalam td, tapi hati-hati tidak menghapus label pseudonya.
                        // Cara paling aman mengakses text node di td pertama:
                        const tdNo = row.cells[0];
                        tdNo.innerHTML = index + 1;
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