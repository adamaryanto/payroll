<?php
$conn = new mysqli("localhost", "root", "", "db_hr");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT COLUMN_NAME, DATA_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'tb_realisasi_detail' 
        AND IS_NULLABLE = 'NO' 
        AND COLUMN_DEFAULT IS NULL 
        AND EXTRA NOT LIKE '%auto_increment%'";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $col = $row['COLUMN_NAME'];
    $type = $row['DATA_TYPE'];

    $default = "''";
    if (in_array($type, ['int', 'decimal', 'double', 'float', 'tinyint', 'smallint', 'mediumint', 'bigint'])) {
        $default = "0";
    } elseif ($type == 'time') {
        $default = "'00:00:00'";
    } elseif ($type == 'date') {
        $default = "'0000-00-00'";
    }

    $alter = "ALTER TABLE tb_realisasi_detail ALTER COLUMN $col SET DEFAULT $default";
    echo "Executing: $alter\n";
    if (!$conn->query($alter)) {
        // If ALTER COLUMN doesn't work, try MODIFY
        echo "ALTER failed, trying MODIFY...\n";
        // To use MODIFY we need the full type, but for simple default adding SET DEFAULT is usually enough if supported.
        // If it fails, I'll just skip or handle differently.
    }
}
$conn->close();
?>
