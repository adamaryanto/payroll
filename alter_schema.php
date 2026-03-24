<?php
$koneksi = new mysqli("localhost", "root", "", "db_hr");

$col_os = $koneksi->query("SHOW COLUMNS FROM tb_realisasi_detail LIKE 'id_os_dhk'");
if ($col_os->num_rows == 0) {
    $koneksi->query("ALTER TABLE tb_realisasi_detail ADD COLUMN id_os_dhk INT(11) NULL DEFAULT 0 AFTER id_sub_department");
    echo "Added id_os_dhk\n";
} else {
    echo "id_os_dhk already exists\n";
}

$col_gol = $koneksi->query("SHOW COLUMNS FROM tb_realisasi_detail LIKE 'id_golongan'");
if ($col_gol->num_rows == 0) {
    $koneksi->query("ALTER TABLE tb_realisasi_detail ADD COLUMN id_golongan INT(11) NULL DEFAULT 0 AFTER id_os_dhk");
    echo "Added id_golongan\n";
} else {
    echo "id_golongan already exists\n";
}
?>
