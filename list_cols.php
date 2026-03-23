<?php
$conn = new mysqli("localhost", "root", "", "db_hr");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'tb_realisasi_detail'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo $row['COLUMN_NAME'] . "\n";
}
$conn->close();
?>
