<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_hr';

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

// Get all Borongan employees
$result = $koneksi->query("SELECT id_karyawan FROM ms_karyawan WHERE golongan = 'Borongan'");
$ids = [];
while ($row = $result->fetch_assoc()) {
    $ids[] = $row['id_karyawan'];
}

$categories = ['Bulanan', 'Mingguan', 'Harian'];
$count = 0;

foreach ($ids as $id) {
    $randomCategory = $categories[array_rand($categories)];
    $update = $koneksi->query("UPDATE ms_karyawan SET golongan = '$randomCategory' WHERE id_karyawan = '$id'");
    if ($update) {
        $count++;
    }
}

echo "Berhasil memigrasi $count karyawan Borongan ke kategori acak (Bulanan/Mingguan/Harian).\n";
$koneksi->close();
?>
