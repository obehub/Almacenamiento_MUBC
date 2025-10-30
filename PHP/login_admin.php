<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Sólo aceptar POST
    header('Location: ../login_admin.html');
    exit;
}

require 'conn.php';

$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validaciones básicas
if ($usuario === '' || $password === '') {
    header('Location: ../login_admin.html?error=1');
    exit;
}

// --------- Suposición razonable: existe una tabla llamada `admins` con columnas `usuario` y `password` ---------
// Si su proyecto usa otro nombre de tabla o columnas, cambiar la consulta abajo.

$stmt = mysqli_prepare($conn, "SELECT password FROM administradores WHERE usuario = ? LIMIT 1");
if (!$stmt) {
    // Error de preparación: no exponer detalles en producción
    header('Location: ../login_admin.html?error=1');
    exit;
}

mysqli_stmt_bind_param($stmt, 's', $usuario);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    // Usuario no existe
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: ../login_admin.html?error=1');
    exit;
}

mysqli_stmt_bind_result($stmt, $hash_or_pass);
mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);

$authenticated = false;

// Intentar verificar con password_verify (si se almacenó hash)
if (function_exists('password_verify') && password_verify($password, $hash_or_pass)) {
    $authenticated = true;
} else {
    // Si no es hash, permitir comparación directa (legacy/plain text)
    if (hash_equals($hash_or_pass, $password)) {
        $authenticated = true;
    }
}

if ($authenticated) {
    // Inicio de sesión seguro
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    // Guardar sólo lo necesario en sesión
    $_SESSION['admin_usuario'] = $usuario;
    // Redirigir al panel de administración
    header('Location: ../admin.html');
    exit;
} else {
    // Credenciales inválidas
    mysqli_close($conn);
    header('Location: ../login_admin.html?error=1');
    exit;
}

?>
