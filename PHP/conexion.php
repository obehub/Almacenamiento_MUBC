<?php
// Datos de conexión a Neon (ajusta los valores según tu proyecto)
$host = 'ep-crimson-mode-ae1rzfg5-pooler.c-2.us-east-2.aws.neon.tech';
$port = '5432';
$dbname = 'neondb';
$user = 'neondb_owner';
$password = 'npg_iQjB8QqVwU4RC'; // tu contraseña real de Neon

try {
    // Conexión usando PDO con SSL obligatorio
    $conexion = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "✅ Conexión exitosa a la base de datos Neon.";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>
