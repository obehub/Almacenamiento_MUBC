<?php
    $conexion   = mysqli_connect("localhost", "obed", "070911", "mubc");
    if (!$conexion) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
