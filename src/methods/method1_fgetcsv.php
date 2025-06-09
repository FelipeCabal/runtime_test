<?php
function method1_fgetcsv($conn, $batchsize)
{
    set_time_limit(0);
    ini_set('memory_limit', '-1');

    $file = __DIR__ . '/../../csv/clientes.csv';
    $fp = fopen($file, 'r');
    if (!$fp) die("No se pudo abrir el archivo CSV");
    fgetcsv($fp, 0, ";"); // salta encabezado con punto y coma

    $start = microtime(true);
    $count = 0;
    $batchSize = $batchsize ?: 1000;
    $rows = [];

    while (($raw = fgetcsv($fp, 0, ";")) !== false) {
        if (count($raw) !== 16) {
            error_log("Línea inválida: " . implode('|', $raw));
            continue;
        }
        // Preparar valores con escape para SQL
        $vals = array_map(function ($v) use ($conn) {
            if ($v === null || $v === '') return 'NULL';
            return "'" . pg_escape_string($conn, $v) . "'";
        }, $raw);

        $rows[] = '(' . implode(',', $vals) . ')';
        $count++;

        if (count($rows) >= $batchSize) {
            $sql = "INSERT INTO clientes (id, genero, edad, nivel_academico, estrato, ciudad_residencia, cant_hijos, num_salarios_minimos, pensionado, tipo_tarjeta, intencion_tarjeta_estab, cant_articulos_comprados, tipo_articulo_mas_comprado, mes_mas_compra, compra_en_quincena, articulo_mayor_intencion) VALUES " . implode(',', $rows);
            $res = pg_query($conn, $sql);
            if (!$res) error_log("Error SQL: " . pg_last_error($conn));
            $rows = [];
        }
    }

    if (!empty($rows)) {
        $sql = "INSERT INTO clientes (id, genero, edad, nivel_academico, estrato, ciudad_residencia, cant_hijos, num_salarios_minimos, pensionado, tipo_tarjeta, intencion_tarjeta_estab, cant_articulos_comprados, tipo_articulo_mas_comprado, mes_mas_compra, compra_en_quincena, articulo_mayor_intencion) VALUES " . implode(',', $rows);
        $res = pg_query($conn, $sql);
        if (!$res) error_log("Error SQL final: " . pg_last_error($conn));
    }

    fclose($fp);
    return ['time' => microtime(true) - $start, 'rows' => $count];
}
