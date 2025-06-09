<?php
function method2_loaddata($mysqli)
{
    $file = addslashes(realpath(__DIR__ . '/../../csv/clientes.csv'));
    $sql = "
      LOAD DATA LOCAL INFILE '$file'
      INTO TABLE clientes
      FIELDS TERMINATED BY ','
      ENCLOSED BY '\"'
      LINES TERMINATED BY '\\n'
      IGNORE 1 LINES
    ";
    $start = microtime(true);
    $rows = 0;
    $error = '';

    try {
        $mysqli->query($sql);
        $rows = $mysqli->affected_rows;
    } catch (mysqli_sql_exception $e) {
        $error = $e->getMessage();
    }

    return ['time' => microtime(true) - $start, 'rows' => $rows, 'error' => $error];
}
