<?php
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_LOCAL_INFILE, true);
$mysqli->real_connect('localhost', 'root', '', 'clientesdb');
if ($mysqli->connect_errno) {
    die("Conexión fallida: " . $mysqli->connect_error);
}
