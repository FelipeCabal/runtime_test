<?php

function method2_copy($conn)
{
    $file = realpath(__DIR__ . '/../../csv/clientes.csv');
    $start = microtime(true);

    $handle = fopen($file, 'r');
    if (!$handle) {
        return ['error' => 'No se pudo abrir el archivo CSV'];
    }

    $sql = "COPY clientes (id, genero, edad, nivel_academico, estrato, ciudad_residencia, cant_hijos, num_salarios_minimos, pensionado, tipo_tarjeta, intencion_tarjeta_estab, cant_articulos_comprados, tipo_articulo_mas_comprado, mes_mas_compra, compra_en_quincena, articulo_mayor_intencion) FROM STDIN WITH (FORMAT csv, DELIMITER E';', NULL 'NULL', HEADER false)";
    $res = pg_query($conn, $sql);
    if (!$res) {
        fclose($handle);
        return ['error' => pg_last_error($conn)];
    }

    $rows = 0;
    while (($line = fgets($handle)) !== false) {
        pg_put_line($conn, $line);
        $rows++;
    }
    pg_end_copy($conn);
    fclose($handle);

    return ['time' => microtime(true) - $start, 'rows' => $rows];
}
