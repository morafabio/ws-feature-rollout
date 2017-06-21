<?php

$csv = 'random.csv';

$fh = fopen($csv, 'w');
for ($i = 0; $i <= 100000; $i++) {
    $value = mt_rand(0, 1);
    fputcsv($fh, [$value]);
}

fclose($fh);
