<?php
// add_admin.php
// Recibe POST (usuario, email, password) y devuelve JSON { success: bool, message: string }

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo json_encode(['success' => false, 'message' => 'Método no permitido']);
	exit;
}

require __DIR__ . '/conexion.php';

$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validaciones básicas
if ($usuario === '' || $password === '' || $email === '') {
	echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
	exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	echo json_encode(['success' => false, 'message' => 'Email inválido']);
	exit;
}

// Evitar duplicados por usuario o email
$checkSql = "SELECT id FROM administradores WHERE usuario = ? OR email = ? LIMIT 1";
$stmt = mysqli_prepare($conexion, $checkSql);
if (!$stmt) {
	echo json_encode(['success' => false, 'message' => 'Error en la base de datos (preparación)']);
	exit;
}

mysqli_stmt_bind_param($stmt, 'ss', $usuario, $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
	mysqli_stmt_close($stmt);
	echo json_encode(['success' => false, 'message' => 'El usuario o el email ya están registrados']);
	exit;
}
mysqli_stmt_close($stmt);

// Hashear la contraseña
if (function_exists('password_hash')) {
	$hash = password_hash($password, PASSWORD_DEFAULT);
} else {
	// Fallback muy básico (no recomendado en producción)
	$hash = sha1($password);
}

$insertSql = "INSERT INTO administradores (usuario, email, password) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conexion, $insertSql);
if (!$stmt) {
	echo json_encode(['success' => false, 'message' => 'Error en la base de datos (preparación insert)']);
	exit;
}

mysqli_stmt_bind_param($stmt, 'sss', $usuario, $email, $hash);
$ok = mysqli_stmt_execute($stmt);
if ($ok) {
	mysqli_stmt_close($stmt);
	mysqli_close($conexion);
	echo json_encode(['success' => true, 'message' => 'Administrador agregado correctamente']);
	exit;
} else {
	$err = mysqli_error($conexion);
	mysqli_stmt_close($stmt);
	mysqli_close($conexion);
	// No exponer $err en producción; aquí lo incluimos para facilitar depuración local
	echo json_encode(['success' => false, 'message' => 'No se pudo agregar el administrador', 'error' => $err]);
	exit;
}

?>
