<?php
$conn = new mysqli("localhost", "root", "", "db_hr");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'tb_realisasi_detail' AND IS_NULLABLE = 'NO' AND COLUMN_DEFAULT IS NULL AND EXTRA NOT LIKE '%auto_increment%'";
$result = $conn->query($sql);

echo "Starting fixes...\n";
while ($row = $result->fetch_assoc()) {
    $col = $row['COLUMN_NAME'];
    $type = $row['DATA_TYPE'];

    $default = "0";
    if ($type == 'time') $default = "'00:00:00'";
    elseif ($type == 'date') $default = "'0000-00-00'";
    elseif (strpos($type, 'char') !== false || strpos($type, 'text') !== false) $default = "''";

    $alter = "ALTER TABLE tb_realisasi_detail ALTER COLUMN $col SET DEFAULT $default";
    echo "COL: $col | ";
    if ($conn->query($alter)) {
        echo "FIXED\n";
    } else {
        echo "FAILED: " . $conn->error . "\n";
    }
}
$conn->close();
?>
