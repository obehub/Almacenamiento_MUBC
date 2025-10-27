<?php
// Datos de conexión (extraídos del connection string de Neon)
$host = 'ep-crimson-mode-ae1rzfg5-pooler.c-2.us-east-2.aws.neon.tech';
$port = 5432;
$dbname = 'neondb';
$user = 'neondb_owner';
$password = 'npg_iQJB0qYwU4RC';

// Configuración SSL obligatoria para Neon
$sslmode = 'require';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=$sslmode";
    $conexion = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Modo de error
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Resultado como array asociativo
    ]);

    echo "✅ Conexión exitosa a Neon Database";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>

PGHOST='ep-crimson-mode-ae1rzfg5-pooler.c-2.us-east-2.aws.neon.tech'
PGDATABASE='neondb'
PGUSER='neondb_owner'
PGPASSWORD='npg_iQJB0qYwU4RC'