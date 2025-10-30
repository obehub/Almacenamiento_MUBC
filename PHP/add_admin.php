<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
require 'conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$usuario  = trim($_POST['usuario'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$cedula   = trim($_POST['cedula'] ?? '');

// 🔹 Verificar si la cédula existe en la tabla registro
$check = $conn->prepare("SELECT id FROM registro WHERE cedula = ?");
$check->bind_param('s', $cedula);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Esta cédula no existe en la tabla registro']);
    exit;
}

// 🔹 Insertar en tabla admin
$stmt = $conn->prepare("INSERT INTO administradores (usuario, email, password, cedula) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $usuario, $email, $password, $cedula);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Administrador agregado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al insertar administrador: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
