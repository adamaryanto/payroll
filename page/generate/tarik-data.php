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
</head>

<body bgcolor="#caffcb">

	<H3>Download Log Data</H3>

	<?php
	//include "koneksi.php";
	//$IP= $HTTP_GET_VARS["121.0.0.144"];
	//$Key= $HTTP_GET_VARS[""];
	$IP = $ipmesin;
	$Key = "0";
	if ($IP == "") $IP = "192.168.110.201";
	if ($Key == "") $Key = "";

	?>

	<form action="tarik-data.php">
		IP Address: <input type="Text" name="ip" value="<?php echo $IP; ?>" size=15><BR>
		Comm Key: <input type="Text" name="key" size="5" value="<?php echo $Key; ?>"><BR><BR>
		<!-- <input type="Submit" value="Download"> -->
	</form>
	<BR>

	<?php
	if ($IP != "") {
	?>
		<table cellspacing="2" cellpadding="2" border="1">
			<tr align="center">
				<td><B>Data</B></td>
				<td><B>UserID</B></td>
				<td width="200"><B>Tanggal & Jam</B></td>
				<td><B>Verifikasi</B></td>
				<td><B>Status</B></td>
			</tr>
			<?php
			$Connect = fsockopen($IP, $port, $errno, $errstr, 1);

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
			} else echo "Koneksi Gagal";

			include("parse.php");
			$buffer = Parse_Data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
			$buffer = explode("\r\n", $buffer);
			for ($a = 0; $a < count($buffer); $a++) {
				$data = Parse_Data($buffer[$a], "<Row>", "</Row>");
				$PIN = Parse_Data($data, "<PIN>", "</PIN>");
				$DateTime = Parse_Data($data, "<DateTime>", "</DateTime>");
				$Verified = Parse_Data($data, "<Verified>", "</Verified>");
				$Status = Parse_Data($data, "<Status>", "</Status>");

				if ($PIN != '' && $DateTime != '') {
					$koneksi->query("insert into tb_record (data,userid,tgl,verifikasi,status,detail_waktu) values ('$data','$PIN','$DateTime','$Verified','$Status','$DateTime') ");
			?>
					<tr align="center">
						<td><?php echo $data ?></td>
						<td><?php echo $PIN ?></td>
						<td><?php echo $DateTime ?></td>
						<td><?php echo $Verified ?></td>
						<td><?php echo $Status ?></td>
					</tr>
			<?php }
			} ?>
		</table>
	<?php
	}
	?>
</body>
</html>