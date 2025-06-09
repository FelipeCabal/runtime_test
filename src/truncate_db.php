<?php
function limpiarTabla($conn)
{
    $result = pg_query($conn, "TRUNCATE TABLE clientes");
    if ($result) {
        echo "Tabla 'clientes' vaciada exitosamente.\n";
    } else {
        echo "Error al vaciar la tabla: " . pg_last_error($conn) . "\n";
    }
}
