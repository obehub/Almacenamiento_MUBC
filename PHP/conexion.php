<?php
$conexion = mysqli_connect("localhost", "obed", "070911", "mubc_base");

if (!$conexion) {
    die("Connection failed: " . mysqli_error());
}
?>