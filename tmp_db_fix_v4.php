<?php
$conn = new mysqli("localhost", "root", "", "db_hr");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT COLUMN_NAME, COLUMN_TYPE, DATA_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'tb_realisasi_detail' 
        AND IS_NULLABLE = 'NO' 
        AND COLUMN_DEFAULT IS NULL 
        AND EXTRA NOT LIKE '%auto_increment%'";

echo "Running schema fix...\n";
$result = $conn->query($sql);
if (!$result) die("Query failed: " . $conn->error);

while ($row = $result->fetch_assoc()) {
    $col = $row['COLUMN_NAME'];
    $type = $row['DATA_TYPE'];
    $full_type = $row['COLUMN_TYPE'];

    $default_val = "''";
    if (in_array($type, ['int', 'decimal', 'double', 'float', 'tinyint', 'smallint', 'mediumint', 'bigint'])) {
        $default_val = "0";
    } elseif ($type == 'time') {
        $default_val = "'00:00:00'";
    } elseif ($type == 'date') {
        $default_val = "'0000-00-00'";
    }

    // Proactively clean up NULLs/invalid values
    $conn->query("UPDATE tb_realisasi_detail SET $col = $default_val WHERE $col IS NULL");
    
    // MODIFY COLUMN
    $alter = "ALTER TABLE tb_realisasi_detail MODIFY COLUMN $col $full_type NOT NULL DEFAULT $default_val";
    echo "COL: $col | TYPE: $type | ";
    if ($conn->query($alter)) {
        echo "SUCCESS\n";
    } else {
        echo "FAILED: " . $conn->error . "\n";
    }
}
$conn->close();
?>
