<?php
// Cargar las variables del archivo .env
$host = "localhost";
$root = "obed";
$password = "070911";
$database = "mubc_base";

// Crear conexión
$conn = mysqli_connect($host, $root, $password, $database);

// Verificar conexión
if (!$conn) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}
?>