<?php
require 'conexion.php';

$registros_por_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $registros_por_pagina;

$busqueda = isset($_GET['buscar']) ? mysqli_real_escape_string($conexion, $_GET['buscar']) : '';
$where = '';
if (!empty($busqueda)) {
    $where = "WHERE nombre LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%' 
              OR cedula LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%'";
}

$sql_total = "SELECT COUNT(*) as total FROM registro $where";
$result_total = mysqli_query($conexion, $sql_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_registros = $row_total['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

$consulta = "SELECT * FROM registro $where LIMIT $inicio, $registros_por_pagina";
$resultado = mysqli_query($conexion, $consulta);

$registros = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $fila['id'] = $fila['id'] ?? $fila['id_registro'] ?? null;
    $registros[] = $fila;
}

mysqli_close($conexion);

header('Content-Type: application/json');
echo json_encode([
    'data' => $registros,
    'pagina' => $pagina,
    'total_paginas' => $total_paginas
]);
