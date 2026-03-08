<?php
$file = 'database/db_hr.sql';
$content = file_get_contents($file);
$start_marker = "INSERT INTO `ms_karyawan` (";
$end_marker = ");";
$start_pos = strpos($content, $start_marker);
$end_pos = strpos($content, $end_marker, $start_pos);
$insert_block = substr($content, $start_pos, $end_pos - $start_pos + 2);
$lines = explode("\n", $insert_block);
$new_lines = [];
foreach ($lines as $line) {
    if (strpos($line, "INSERT INTO") !== false) { $new_lines[] = $line; continue; }
    $trimmed = trim($line);
    if (empty($trimmed)) continue;
    $suffix = '';
    if (substr($trimmed, -1) == ',' || substr($trimmed, -1) == ';') { $suffix = substr($trimmed, -1); $trimmed = substr($trimmed, 0, -1); }
    if (substr($trimmed, 0, 1) == '(' && substr($trimmed, -1) == ')') {
        $values_str = substr($trimmed, 1, -1);
        $values = preg_split("/,(?=(?:[^']*'[^']*')*[^']*$)/", $values_str);
        // upah_harian=17, upah_mingguan=18, upah_bulanan=19 (0-indexed) in current INSERT
        if (count($values) > 21) { unset($values[17]); unset($values[18]); unset($values[19]); $new_lines[] = "  (" . implode(",", array_values($values)) . ")" . $suffix; }
        else { $new_lines[] = "  " . $trimmed . $suffix; }
    } else { $new_lines[] = $line; }
}
$new_insert_block = implode("\n", $new_lines);
$new_content = substr_replace($content, $new_insert_block, $start_pos, $end_pos - $start_pos + 2);
file_put_contents($file, $new_content);
echo "upah data cleaned from db_hr.sql\n";
?>
