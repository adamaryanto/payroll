<?php
$conn = new mysqli("localhost", "root", "", "db_hr");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SHOW COLUMNS FROM tb_realisasi_detail";
$result = $conn->query($sql);
$out = "";
while ($row = $result->fetch_assoc()) {
    $out .= $row['Field'] . " | " . $row['Type'] . " | " . $row['Null'] . " | " . ($row['Default'] === null ? 'NULL' : $row['Default']) . "\n";
}
file_put_contents("full_cols.txt", $out);
echo "Done\n";
$conn->close();
?>
