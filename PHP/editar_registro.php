<?php
require 'conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conexion, $_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $lugar = mysqli_real_escape_string($conexion, $_POST['lugar']);
    $mesa = mysqli_real_escape_string($conexion, $_POST['mesa']);
    $rf_por = mysqli_real_escape_string($conexion, $_POST['rf_por']);

    $sql = "UPDATE registro SET 
            nombre = '$nombre',
            apellido = '$apellido',
            cedula = '$cedula',
            telefono = '$telefono',
            lugar = '$lugar',
            mesa = '$mesa',
            rf_por = '$rf_por'
            WHERE id_registro = $id";

    if (mysqli_query($conexion, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Registro actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el registro: ' . mysqli_error($conexion)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

mysqli_close($conexion);
?>