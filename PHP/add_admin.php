<?php
header('Content-Type: application/json; charset=utf-8');
require 'conn.php'; // Tu conexión existente

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$usuario = trim($_POST['usuario'] ?? '');
$email = trim($_POST['email'] ?? '');
$cedula = trim($_POST['cedula'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($usuario === '' || $email === '' || $cedula === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

// 1️⃣ Verificar si la cédula está en la tabla de registro
$sql_check = "SELECT * FROM registro WHERE cedula = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("s", $cedula);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'La cédula no se encuentra en el registro']);
    exit;
}

// 2️⃣ Verificar si ya existe un administrador con esa cédula
$sql_exists = "SELECT * FROM administradores WHERE cedula = ?";
$stmt_exists = $conexion->prepare($sql_exists);
$stmt_exists->bind_param("s", $cedula);
$stmt_exists->execute();
$result_exists = $stmt_exists->get_result();

if ($result_exists->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Este administrador ya está registrado']);
    exit;
}

// 3️⃣ Insertar nuevo administrador
$sql_insert = "INSERT INTO administradores (usuario, email, cedula, password) VALUES (?, ?, ?, ?)";
$stmt_insert = $conexion->prepare($sql_insert);
$stmt_insert->bind_param("ssss", $usuario, $email, $cedula, $password);

if ($stmt_insert->execute()) {
    echo json_encode(['success' => true, 'message' => 'Administrador agregado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al agregar el administrador']);
}

$stmt_check->close();
$stmt_exists->close();
$stmt_insert->close();
$conexion->close();
