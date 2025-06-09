<?php
// Método 1: lectura línea a línea usando fgetcsv()
function method1_fgetcsv($mysqli)
{
    $fp = fopen(__DIR__ . '/../../csv/clientes.csv', 'r');
    fgetcsv($fp); // Omitir encabezado
    $start = microtime(true);
    $count = 0;

    while (($row = fgetcsv($fp)) !== false) {
        if (count($row) !== 16) continue;
        $stmt = $mysqli->prepare(
            "INSERT INTO clientes VALUES (" . implode(',', array_fill(0, 16, '?')) . ")"
        );
        $stmt->bind_param(str_repeat('i', 16), ...$row);
        $stmt->execute();
        $count++;
    }
    fclose($fp);
    return ['time' => microtime(true) - $start, 'rows' => $count];
}
