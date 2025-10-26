<?php
$host = "ep-green-frost-123456-pooler.us-east-2.aws.neon.tech";
$port = "5432";
$dbname = "neondb";
$user = "obed";
$password = "070911";

try {
    // Conexión usando PDO
    $conexion = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "✅ Conexión exitosa a la base de datos Neon.";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>