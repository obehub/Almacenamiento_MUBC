<?php
header('Content-Type: application/json; charset=utf-8');

// Solo aceptar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

require __DIR__ . '/conn.php'; // 🔹 corregido con la barra

// Obtener y limpiar los datos
$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';

// Validaciones básicas
if ($usuario === '' || $password === '' || $email === '' || $cedula === '') {
    echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email inválido']);
    exit;
}

// 🔹 1. Verificar que la cédula exista en la tabla 'registro'
$checkRegistroSql = "SELECT id FROM registro WHERE cedula = ? LIMIT 1";
$stmtRegistro = mysqli_prepare($conn, $checkRegistroSql);
if (!$stmtRegistro) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la verificación de cédula en registro']);
    exit;
}

mysqli_stmt_bind_param($stmtRegistro, 's', $cedula);
mysqli_stmt_execute($stmtRegistro);
mysqli_stmt_store_result($stmtRegistro);

if (mysqli_stmt_num_rows($stmtRegistro) === 0) {
    mysqli_stmt_close($stmtRegistro);
    echo json_encode(['success' => false, 'message' => 'La cédula no está registrada en la tabla "registro".']);
    exit;
}
mysqli_stmt_close($stmtRegistro);

// 🔹 2. Verificar duplicados en la tabla 'administradores'
$checkAdminSql = "SELECT id FROM administradores WHERE usuario = ? OR email = ? OR cedula = ? LIMIT 1";
$stmtAdmin = mysqli_prepare($conn, $checkAdminSql);
if (!$stmtAdmin) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la verificación de administrador']);
    exit;
}

mysqli_stmt_bind_param($stmtAdmin, 'sss', $usuario, $email, $cedula);
mysqli_stmt_execute($stmtAdmin);
mysqli_stmt_store_result($stmtAdmin);

if (mysqli_stmt_num_rows($stmtAdmin) > 0) {
    mysqli_stmt_close($stmtAdmin);
    echo json_encode(['success' => false, 'message' => 'El usuario, email o cédula ya están registrados como administrador.']);
    exit;
}
mysqli_stmt_close($stmtAdmin);

// 🔹 3. Hashear la contraseña de forma segura
$hash = password_hash($password, PASSWORD_DEFAULT);

// 🔹 4. Insertar el nuevo administrador
$insertSql = "INSERT INTO administradores (usuario, password, email, cedula) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $insertSql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la inserción de datos']);
    exit;
}

mysqli_stmt_bind_param($stmt, 'ssss', $usuario, $hash, $email, $cedula);
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Administrador agregado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el administrador en la base de datos']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
