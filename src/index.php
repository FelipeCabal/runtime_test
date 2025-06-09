<?php
require 'db_config.php';
require 'methods/method1_fgetcsv.php';
require 'methods/method2_loaddata.php';
require 'methods/method3_str_getcsv.php';

$res1 = method1_fgetcsv($mysqli);
$res2 = method2_loaddata($mysqli);
$res3 = method3_str_getcsv($mysqli);

$mysqli->close();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Import Results</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center
        }

        th {
            background: #f2f2f2
        }
    </style>
</head>

<body>
    <h1>Resultados de Importación</h1>
    <table>
        <tr>
            <th>Método</th>
            <th>Tiempo (s)</th>
            <th>Filas Insertadas</th>
        </tr>
        <tr>
            <td>1. fgetcsv()</td>
            <td><?= round($res1['time'], 3) ?></td>
            <td><?= $res1['rows'] ?></td>
        </tr>
        <tr>
            <td>2. LOAD DATA LOCAL INFILE</td>
            <td><?= round($res2['time'], 3) ?></td>
            <td><?= $res2['rows'] ?: '0' ?></td>
            <td><?= $res2['error'] ? '❌ ' . htmlspecialchars($res2['error']) : '✔️' ?></td>
        </tr>
        <tr>
            <td>3. str_getcsv() en lotes</td>
            <td><?= round($res3['time'], 3) ?></td>
            <td><?= $res3['rows'] ?></td>
        </tr>
    </table>
</body>

</html>