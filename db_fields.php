<?php
$k = new mysqli('localhost', 'root', '', 'db_hr');
$res = $k->query("DESC tb_realisasi_detail");
$fields = [];
while($row = $res->fetch_assoc()) {
    $fields[] = $row['Field'];
}
echo implode(", ", $fields) . "\n";

$res2 = $k->query("SELECT id_rkk_detail FROM tb_rkk_detail LIMIT 1");
$row2 = $res2->fetch_assoc();
echo "Sample id_rkk_detail: " . $row2['id_rkk_detail'] . "\n";
?>
