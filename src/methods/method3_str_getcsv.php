<?php
// Método 3: leer en memoria con file() y str_getcsv(), luego insertar en lotes
function method3_str_getcsv($mysqli)
{
    $lines = file(__DIR__ . '/../../csv/clientes.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    array_shift($lines); // Omitir encabezado
    $batchSize = 1000;
    $start = microtime(true);
    $count = 0;

    foreach (array_chunk($lines, $batchSize) as $chunk) {
        $values = [];
        foreach ($chunk as $line) {
            $row = str_getcsv($line);
            if (count($row) !== 16) continue;
            $values[] = "(" . implode(',', array_map('intval', $row)) . ")";
            $count++;
        }
        if ($values) {
            $sql = "INSERT INTO clientes VALUES " . implode(',', $values);
            $mysqli->query($sql);
        }
    }
    return ['time' => microtime(true) - $start, 'rows' => $count];
}
