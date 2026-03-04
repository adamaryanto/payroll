<?php
$host='localhost'; 
$user='root'; 
$pass=''; 
$database='db_hr';
$koneksi=mysqli_connect($host, $user, $pass, $database); 
if (!$koneksi) { die("Connection failed: " . mysqli_connect_error()); }

$result = $koneksi->query("SHOW TABLES");
while($row = $result->fetch_array()) {
    $table = $row[0];
    echo "TABLE: $table\n";
    $desc = $koneksi->query("DESCRIBE $table");
    while($col = $desc->fetch_assoc()) {
        echo "  {$col['Field']} - {$col['Type']}\n";
    }
    echo "\n";
}
?>
