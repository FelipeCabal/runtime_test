<?php
require 'db_config.php'; // Contiene $conn con conexión pg_connect
require 'methods/method1_fgetcsv.php';
require 'methods/method2_copy.php';
require 'methods/method3_str_getcsv.php';
require 'truncate_db.php';

limpiarTabla($conn);
$res1 = method1_fgetcsv($conn);

limpiarTabla($conn);
$res2 = method2_copy($conn);

limpiarTabla($conn);
$res3 = method3_str_getcsv($conn, $batchSize);

pg_close($conn);
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
            <th>Estado</th>
        </tr>
        <tr>
            <td>1. Fila x Fila</td>
            <td><?= round($res1['time'], 3) ?></td>
            <td><?= $res1['rows'] ?></td>
            <td>✔️ OK</td>
        </tr>
        <tr>
            <td>2. COPY</td>
            <td><?= round($res2['time'], 3) ?></td>
            <td><?= $res2['rows'] ?? 0 ?></td>
            <td><?= isset($res2['error']) ? '❌ ' . htmlspecialchars($res2['error']) : '✔️ OK' ?></td>
        </tr>
        <tr>
            <td>3. en lotes</td>
            <td><?= round($res3['time'], 3) ?></td>
            <td><?= $res3['rows'] ?></td>
            <td>✔️ OK</td>
        </tr>
    </table>
</body>

</html>