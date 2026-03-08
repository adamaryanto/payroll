<?php
$file = 'database/db_hr.sql';
$content = file_get_contents($file);

$start_marker = "INSERT INTO `ms_karyawan` (";
$end_marker = ");";

$start_pos = strpos($content, $start_marker);
if ($start_pos === false) exit("Insert not found");

$end_pos = strpos($content, $end_marker, $start_pos);
if ($end_pos === false) exit("End marker not found");

$insert_block = substr($content, $start_pos, $end_pos - $start_pos + 2);

$lines = explode("\n", $insert_block);
$new_lines = [];

foreach ($lines as $line) {
    if (strpos($line, "INSERT INTO") !== false) {
        $new_lines[] = $line;
        continue;
    }
    
    $trimmed = trim($line);
    if (empty($trimmed)) continue;
    
    $suffix = '';
    if (substr($trimmed, -1) == ',' || substr($trimmed, -1) == ';') {
        $suffix = substr($trimmed, -1);
        $trimmed = substr($trimmed, 0, -1);
    }
    
    if (substr($trimmed, 0, 1) == '(' && substr($trimmed, -1) == ')') {
        $values_str = substr($trimmed, 1, -1);
        $values = preg_split("/,(?=(?:[^']*'[^']*')*[^']*$)/", $values_str);
        
        if (count($values) > 25) {
            // Original columns were 27. We removed 2 (NPWP & BPJS).
            // NPWP was at index 18, BPJS at index 19.
            unset($values[18]);
            unset($values[19]);
            $new_line = "(". implode(",", array_values($values)) . ")" . $suffix;
            $new_lines[] = "  " . $new_line;
        } else {
            $new_lines[] = "  " . $trimmed . $suffix;
        }
    } else {
        $new_lines[] = $line;
    }
}

$new_insert_block = implode("\n", $new_lines);
$new_content = substr_replace($content, $new_insert_block, $start_pos, $end_pos - $start_pos + 2);

file_put_contents($file, $new_content);
echo "db_hr.sql data cleaned successfully.\n";
?>
