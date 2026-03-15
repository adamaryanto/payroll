<?php
/**
 * DATABASE MIGRATION SCRIPT
 * Function: Updates database structure to support Dynamic Fields and New Penalties
 * Date: 2024-03-15
 */

include "koneksi.php";

echo "<h2>Starting Database Migration...</h2>";

// --- 1. Master Tables Creation ---

// ms_os_dhk
$koneksi->query("CREATE TABLE IF NOT EXISTS `ms_os_dhk` (
  `id_os_dhk` int(11) NOT NULL AUTO_INCREMENT,
  `OS_DHK` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_os_dhk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
echo "Checked Table: ms_os_dhk<br>";

// ms_golongan
$koneksi->query("CREATE TABLE IF NOT EXISTS `ms_golongan` (
  `id_golongan` int(11) NOT NULL AUTO_INCREMENT,
  `golongan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_golongan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
echo "Checked Table: ms_golongan<br>";


// --- 2. Add Columns to ms_karyawan ---

function addColumn($koneksi, $table, $column, $type) {
    $check = $koneksi->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    if ($check->num_rows == 0) {
        $koneksi->query("ALTER TABLE `$table` ADD `$column` $type");
        echo "Added Column: $table.$column<br>";
        return true;
    }
    return false;
}

addColumn($koneksi, 'ms_karyawan', 'id_os_dhk', 'int(11) DEFAULT 0');
addColumn($koneksi, 'ms_karyawan', 'id_golongan', 'int(11) DEFAULT 0');


// --- 3. Add Columns to tb_denda ---

addColumn($koneksi, 'tb_denda', 'denda_istirahat_keluar', 'decimal(15,2) DEFAULT 0.00');
addColumn($koneksi, 'tb_denda', 'denda_istirahat_masuk', 'decimal(15,2) DEFAULT 0.00');
addColumn($koneksi, 'tb_denda', 'denda_pulang', 'decimal(15,2) DEFAULT 0.00');
addColumn($koneksi, 'tb_denda', 'denda_tidak_lengkap', 'decimal(15,2) DEFAULT 0.00');


// --- 4. Data Migration (OS/DHK & Golongan) ---

// Seeding OS/DHK
$res_os = $koneksi->query("SELECT DISTINCT OS_DHK FROM ms_karyawan WHERE OS_DHK != '' AND OS_DHK IS NOT NULL");
while ($row = $res_os->fetch_assoc()) {
    $val = $koneksi->real_escape_string($row['OS_DHK']);
    $check = $koneksi->query("SELECT id_os_dhk FROM ms_os_dhk WHERE OS_DHK = '$val'");
    if ($check->num_rows == 0) {
        $koneksi->query("INSERT INTO ms_os_dhk (OS_DHK) VALUES ('$val')");
    }
}

// Seeding Golongan
$res_gol = $koneksi->query("SELECT DISTINCT golongan FROM ms_karyawan WHERE golongan != '' AND golongan IS NOT NULL");
while ($row = $res_gol->fetch_assoc()) {
    $val = $koneksi->real_escape_string($row['golongan']);
    $check = $koneksi->query("SELECT id_golongan FROM ms_golongan WHERE golongan = '$val'");
    if ($check->num_rows == 0) {
        $koneksi->query("INSERT INTO ms_golongan (golongan) VALUES ('$val')");
    }
}

// Linking ms_karyawan to IDs
$koneksi->query("UPDATE ms_karyawan k JOIN ms_os_dhk o ON k.OS_DHK = o.OS_DHK SET k.id_os_dhk = o.id_os_dhk WHERE k.id_os_dhk = 0");
$koneksi->query("UPDATE ms_karyawan k JOIN ms_golongan g ON k.golongan = g.golongan SET k.id_golongan = g.id_golongan WHERE k.id_golongan = 0");

echo "<br><b>Migration Finished Successfully!</b><br>";
echo "Please REMOVE this file for security.";
?>
