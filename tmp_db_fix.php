<?php
$conn = new mysqli("localhost", "root", "", "db_hr");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT COLUMN_NAME, DATA_TYPE, COLUMN_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'tb_realisasi_detail' 
        AND IS_NULLABLE = 'NO' 
        AND COLUMN_DEFAULT IS NULL 
        AND EXTRA NOT LIKE '%auto_increment%'";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $col = $row['COLUMN_NAME'];
    $type = $row['DATA_TYPE'];
    $full_type = $row['COLUMN_TYPE'];

    $default = "''";
    if (strpos($type, 'int') !== false || strpos($type, 'decimal') !== false || strpos($type, 'double') !== false || strpos($type, 'float') !== false) {
        $default = "0";
    } elseif (strpos($type, 'time') !== false) {
        $default = "'00:00:00'";
    } elseif (strpos($type, 'date') !== false) {
        $default = "'0000-00-00'";
    } elseif (strpos($type, 'datetime') !== false || strpos($type, 'timestamp') !== false) {
        $default = "CURRENT_TIMESTAMP";
    }

    // Update NULLs first if any (though IS_NULLABLE is NO, they might be empty if recently changed)
    $conn->query("UPDATE tb_realisasi_detail SET $col = $default WHERE $col IS NULL");

    $alter = "ALTER TABLE tb_realisasi_detail MODIFY COLUMN $col $full_type NOT NULL DEFAULT $default";
    echo "Executing: $alter\n";
    if (!$conn->query($alter)) {
        echo "Error: " . $conn->error . "\n";
    }
}
$conn->close();
?>
