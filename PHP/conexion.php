<?php
$host = "localhost";
$usuario = "obed";
$clave = "070911";
$base_datos = "mubc_base";

// Crear conexión
$conexion = mysqli_connect($host, $usuario, $clave, $base_datos);

// Verificar conexión
if (!$conexion) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

// Configurar UTF-8
mysqli_set_charset($conexion, "utf8");
?>
