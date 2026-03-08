<?php
$file = 'database/db_hr.sql';
$content = file_get_contents($file);

// Find the INSERT INTO `ms_karyawan` block
$start_marker = "INSERT INTO `ms_karyawan` (";
$end_marker = ");";

$start_pos = strpos($content, $start_marker);
if ($start_pos === false) exit("Insert not found");

$end_pos = strpos($content, $end_marker, $start_pos);
if ($end_pos === false) exit("End marker not found");

$insert_block = substr($content, $start_pos, $end_pos - $start_pos + 2);

// Split into lines
$lines = explode("\n", $insert_block);
$new_lines = [];

foreach ($lines as $line) {
    if (strpos($line, "INSERT INTO") !== false) {
        $new_lines[] = $line;
        continue;
    }
    
    // Process a row like: (2, 3, 2, 19, ..., '2', '1', 100000, ...),
    // This is naive but often works for simple SQL dumps. 
    // We need to remove the 19th and 20th elements.
    if (preg_match('/^\((.*)\)(,|;)$/', trim($line), $matches)) {
        $values_str = $matches[1];
        $suffix = $matches[2];
        
        // This won't work perfectly if values contain commas. 
        // Let's use a more robust way: str_getcsv if we treat it as CSV.
        // But SQL values aren't exactly CSV (quotes, NULL, etc).
        
        // Alternative: Use a regex that respects quotes.
        $values = preg_split("/,(?=(?:[^']*'[^']*')*[^']*$)/", $values_str);
        
        if (count($values) >= 20) {
            unset($values[18]); // no_npwp
            unset($values[19]); // no_bpjs
            $new_line = "(". implode(",", array_values($values)) . ")" . $suffix;
            $new_lines[] = "  " . $new_line;
        } else {
            $new_lines[] = $line;
        }
    } else {
        $new_lines[] = $line;
    }
}

$new_insert_block = implode("\n", $new_lines);
$new_content = substr_replace($content, $new_insert_block, $start_pos, $end_pos - $start_pos + 2);

file_put_contents($file, $new_content);
echo "db_hr.sql data cleaned.\n";
?>
