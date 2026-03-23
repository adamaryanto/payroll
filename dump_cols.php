<?php
$conn = new mysqli("localhost", "root", "", "db_hr");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SHOW COLUMNS FROM tb_realisasi_detail";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " | " . $row['Type'] . " | " . $row['Null'] . " | " . ($row['Default'] === null ? 'NULL' : $row['Default']) . "\n";
}
$conn->close();
?>
