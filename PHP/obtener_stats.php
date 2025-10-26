<?php
require 'conexion.php';

header('Content-Type: application/json');

// Array para almacenar las estadísticas
$stats = array();

try {
    // Verificar la conexión
    if (!$conexion) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }
    
    // Total de miembros inscritos
    $query_total = "SELECT COUNT(*) as total FROM registro";
    $result_total = mysqli_query($conexion, $query_total);
    
    if (!$result_total) {
        throw new Exception("Error en la consulta de total: " . mysqli_error($conexion));
    }
    
    $row_total = mysqli_fetch_assoc($result_total);
    $stats['total_inscritos'] = intval($row_total['total']);
    
    // Nuevos miembros esta semana
    $query_nuevos = "SELECT COUNT(*) as nuevos FROM registro 
                     WHERE fecha_registro >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 7 DAY)";
    $result_nuevos = mysqli_query($conexion, $query_nuevos);
    
    if (!$result_nuevos) {
        throw new Exception("Error en la consulta de nuevos: " . mysqli_error($conexion));
    }
    
    $row_nuevos = mysqli_fetch_assoc($result_nuevos);
    $stats['nuevos_semana'] = intval($row_nuevos['nuevos']);
    $stats['success'] = true;
    
} catch (Exception $e) {
    $stats['success'] = false;
    $stats['error'] = $e->getMessage();
}

echo json_encode($stats);
mysqli_close($conexion);
?>