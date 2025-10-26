<?php
require 'conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conexion, $_POST['id']);

    $sql = "DELETE FROM registro WHERE id_registro = $id";

    if (mysqli_query($conexion, $sql)) {
        if (mysqli_affected_rows($conexion) > 0) {
            echo json_encode(['success' => true, 'message' => 'Registro eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el registro especificado']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el registro: ' . mysqli_error($conexion)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

mysqli_close($conexion);
?>