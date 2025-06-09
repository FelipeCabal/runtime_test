<?php
function method3_str_getcsv($conn, $batchsize)
{
    $file = __DIR__ . '/../../csv/clientes.csv';
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $start = microtime(true);
    $count = 0;
    $batchSize = $batchsize ?: 1000;
    $rows = [];

    foreach (array_chunk($lines, $batchSize) as $chunk) {
        foreach ($chunk as $line) {
            $parts = explode(";", $line);
            if (count($parts) !== 16) {
                error_log("Línea inválida CHUNK: " . $line);
                continue;
            }
            $vals = array_map(function ($v) {
                if ($v === '' || $v === null) return 'NULL';
                return is_numeric($v) ? $v : "'" . addslashes($v) . "'";
            }, $parts);
            $rows[] = '(' . implode(',', $vals) . ')';
            $count++;
        }
        if ($rows) {
            $sql = "INSERT INTO clientes (id, genero, edad, nivel_academico, estrato, ciudad_residencia, cant_hijos, num_salarios_minimos, pensionado, tipo_tarjeta, intencion_tarjeta_estab, cant_articulos_comprados, tipo_articulo_mas_comprado, mes_mas_compra, compra_en_quincena, articulo_mayor_intencion) VALUES " . implode(',', $rows);
            $res = pg_query($conn, $sql);
            if (!$res) error_log("Error SQL CHUNK: " . pg_last_error($conn));
            $rows = [];
        }
    }

    return ['time' => microtime(true) - $start, 'rows' => $count];
}
