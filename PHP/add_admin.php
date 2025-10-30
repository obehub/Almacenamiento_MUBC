<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo json_encode(['success' => false, 'message' => 'Método no permitido']);
	exit;
}

require __DIR__ . '/conn.php';

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

// --- NUEVA LÓGICA: Verificar que la CÉDULA exista en la tabla 'usuarios' ---
$checkUserSql = "SELECT id FROM usuarios WHERE cedula = ? LIMIT 1";
$stmtUser = mysqli_prepare($conn, $checkUserSql);
if (!$stmtUser) {
	echo json_encode(['success' => false, 'message' => 'Error en la base de datos (preparación de verificación de usuario)']);
	exit;
}

mysqli_stmt_bind_param($stmtUser, 's', $cedula);
mysqli_stmt_execute($stmtUser);
mysqli_stmt_store_result($stmtUser);

if (mysqli_stmt_num_rows($stmtUser) === 0) {
	mysqli_stmt_close($stmtUser);
	echo json_encode(['success' => false, 'message' => 'La cédula no está registrada en la tabla de usuarios.']);
	exit;
}
mysqli_stmt_close($stmtUser);
// --- FIN de la NUEVA LÓGICA ---

// Evitar duplicados por usuario o email en la tabla 'administradores'
// La cédula ya no necesita ser verificada contra 'administradores' si el requisito es que YA sea usuario.
$checkAdminSql = "SELECT id FROM administradores WHERE usuario = ? OR email = ? LIMIT 1";
$stmtAdmin = mysqli_prepare($conn, $checkAdminSql);
if (!$stmtAdmin) {
	echo json_encode(['success' => false, 'message' => 'Error en la base de datos (preparación de verificación de administrador)']);
	exit;
}

mysqli_stmt_bind_param($stmtAdmin, 'ss', $usuario, $email);
mysqli_stmt_execute($stmtAdmin);
mysqli_stmt_store_result($stmtAdmin);

if (mysqli_stmt_num_rows($stmtAdmin) > 0) {
	mysqli_stmt_close($stmtAdmin);
	echo json_encode(['success' => false, 'message' => 'El nombre de usuario o el email ya están registrados como administrador.']);
	exit;
}
mysqli_stmt_close($stmtAdmin);


// Hashear la contraseña
if (function_exists('password_hash')) {
	$hash = password_hash($password, PASSWORD_DEFAULT);
} else {
	// Fallback muy básico (no recomendado en producción)
	$hash = sha1($password);
}

// Inserción en la tabla 'administradores'
$insertSql = "INSERT INTO administradores (usuario, password, email, cedula) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $insertSql);
if (!$stmt) {
	echo json_encode(['success' => false, 'message' => 'Error en la base de datos (preparación insert)']);
	exit;
}

mysqli_stmt_bind_param($stmt, 'ssss', $usuario, $email, $hash, $cedula);
$ok = mysqli_stmt_execute($stmt);
if ($ok) {
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	echo json_encode(['success' => true, 'message' => 'Administrador agregado correctamente']);
	exit;
} else {
	$err = mysqli_error($conn);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	// No exponer $err en producción
	echo json_encode(['success' => false, 'message' => 'No se pudo agregar el administrador', 'error' => $err]);
	exit;
}

?>