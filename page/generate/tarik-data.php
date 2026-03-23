<?php

set_time_limit(0); // unlimited
$tampil = $koneksi->query("SELECT * from tb_mesin");
$data = $tampil->fetch_assoc();
$idmesin = $data['id_mesin'];
$nomesin = $data['no_mesin'];
$namamesin = $data['nama_mesin'];
$ipmesin = $data['ip_mesin'];
$commkey = $data['comm_key'];
$port = $data['port'];
?>
<html>

<head>
	<title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title>
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
			}

			#dataTables-example_wrapper .row:first-child {
				flex-direction: column !important;
				align-items: flex-start !important;
				gap: 15px;
			}

			.dataTables_filter,
			.dataTables_length {
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
				margin-bottom: 1.5rem;
				/* Jarak antar kotak dilebarkan */
				border: 1px solid #e2e8f0;
				border-radius: 12px;
				padding: 16px;
				/* Jarak padding ke dalam kotak dilebarkan */
				box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
				background-color: #fff;
			}

			.table-modern tbody td {
				display: flex;
				flex-direction: column;
				/* Label di atas, data di bawah (stacking) */
				align-items: flex-start;
				padding: 10px 0 !important;
				/* Jarak atas-bawah per baris dilebarkan */
				border: none !important;
				border-bottom: 1px dashed #e2e8f0 !important;
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
				margin-bottom: 6px;
				/* Memberi jarak ke datanya */
				display: block;
				width: 100%;
			}
		}
	</style>
</head>

<body>
	<div class="container-fluid px-2 mt-4 mb-4">
		<div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">

			<div class="border-b border-gray-100 py-4 px-4 md:px-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 bg-white">
				<div>
					<h3 class="text-xl text-blue-600 font-bold m-0"><i class="fas fa-download mr-2"></i>Download Log Data</h3>
				</div>
			</div>

			<?php
			// Tetap mempertahankan variabel asli dan komentar Anda
			//include "koneksi.php";
			//$IP= $HTTP_GET_VARS["121.0.0.144"];
			//$Key= $HTTP_GET_VARS[""];
			$IP = $ipmesin;
			$Key = "0";
			if ($IP == "") $IP = "192.168.110.201";
			if ($Key == "") $Key = "";
			?>

			<form action="tarik-data.php" method="GET">
				<div class="p-4 md:p-5 bg-gray-50 border-b border-gray-100">
					<div class="row items-end">
						<div class="col-md-5 col-sm-12 mb-3 mb-md-0">
							<label class="font-bold text-gray-700 text-sm uppercase tracking-wide">IP Address:</label>
							<input type="text" name="ip" value="<?php echo $IP; ?>" placeholder="Contoh: 192.168.1.1" class="form-control text-base py-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-200" />
						</div>
						<div class="col-md-3 col-sm-12 mb-3 mb-md-0">
							<label class="font-bold text-gray-700 text-sm uppercase tracking-wide">Comm Key:</label>
							<input type="text" name="key" value="<?php echo $Key; ?>" placeholder="0" class="form-control text-base py-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-200" />
						</div>
						<div class="col-md-4 col-sm-12">
							<button type="submit" class="inline-flex items-center border-0 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-sm transition-colors h-[42px]">
								<i class="fas fa-cloud-download-alt mr-2"></i> TARIK DATA SEKARANG
							</button>
						</div>
					</div>
				</div>
			</form>

			<div class="p-0">
				<div class="table-responsive px-3 py-4">
					<?php if ($IP != "") : ?>
						<table class="w-full text-left border-collapse table-modern" id="dataTables-example">
							<thead class="bg-gray-50 border-b border-gray-200">
								<tr>
									<th class="py-3 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle">Data</th>
									<th class="py-3 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle">UserID</th>
									<th class="py-3 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle">Tanggal & Jam</th>
									<th class="py-3 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-center">Verifikasi</th>
									<th class="py-3 px-2 text-[13px] font-bold text-gray-700 uppercase align-middle text-center">Status</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-100">
								<?php
								$buffer = "";
								$Connect = @fsockopen($IP, $port, $errno, $errstr, 1);

								if ($Connect) {
									$soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
									$newLine = "\r\n";
									fputs($Connect, "POST /iWsService HTTP/1.0" . $newLine);
									fputs($Connect, "Content-Type: text/xml" . $newLine);
									fputs($Connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
									fputs($Connect, $soap_request . $newLine);
									$buffer = "";
									while ($Response = fgets($Connect, 1024)) {
										$buffer = $buffer . $Response;
									}
								} else {
									echo "<p class='text-center py-4 text-red-500 font-bold'>Koneksi Gagal ke Mesin!</p>";
								}

								// Gunakan __DIR__ agar include selalu benar kemanapun file ini dipanggil
								include(__DIR__ . "/parse.php");
								$buffer = Parse_Data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
								$buffer = explode("\r\n", $buffer);

								for ($a = 0; $a < count($buffer); $a++) :
									$data_raw = Parse_Data($buffer[$a], "<Row>", "</Row>");
									$PIN = Parse_Data($data_raw, "<PIN>", "</PIN>");
									$DateTime = Parse_Data($data_raw, "<DateTime>", "</DateTime>");
									$Verified = Parse_Data($data_raw, "<Verified>", "</Verified>");
									$Status = Parse_Data($data_raw, "<Status>", "</Status>");

									if ($PIN != '' && $DateTime != '') :
										// Proses simpan ke database
										$koneksi->query("INSERT INTO tb_record (data,userid,tgl,verifikasi,status,detail_waktu) VALUES ('$data_raw','$PIN','$DateTime','$Verified','$Status','$DateTime')");
								?>
										<tr class="hover:bg-gray-50 transition-colors">
											<td data-label="Data" class="py-2 px-2 text-[13px] text-gray-500 font-mono"><?php echo $data_raw ?></td>
											<td data-label="UserID" class="py-2 px-2 text-[15px] font-bold text-gray-900"><?php echo $PIN ?></td>
											<td data-label="Tanggal & Jam" class="py-2 px-2 text-[15px] text-gray-700"><?php echo $DateTime ?></td>
											<td data-label="Verifikasi" class="py-2 px-2 text-[15px] text-center"><span class="badge bg-gray-100 text-gray-700 px-2 py-1 rounded"><?php echo $Verified ?></span></td>
											<td data-label="Status" class="py-2 px-2 text-[15px] text-center font-bold text-blue-600"><?php echo $Status ?></td>
										</tr>
								<?php
									endif;
								endfor;
								?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

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
					search: "Cari:",
					searchPlaceholder: "Cari data...",
					lengthMenu: "Tampilkan _MENU_ data",
					info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
					paginate: {
						previous: "Prev",
						next: "Next"
					}
				}
			});

			// Dihapus style .css('float') bawaan agar tidak bentrok dengan flexbox pada mobile
			$('.dataTables_filter').addClass('mb-3');
			$('.dataTables_length').addClass('mb-3');
		});
	</script>
</body>

</html>