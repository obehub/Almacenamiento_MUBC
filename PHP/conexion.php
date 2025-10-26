<?php
try {
    $conn = new PDO("pgsql:host=ep-crimson-mode-ae1rzfg5-pooler.c-2.us-east-2.aws.neon.tech;dbname=neondb", 
                    "neondb_owner", 
                    "npg_iQJB0qYwU4RC",
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
    echo "Conectado exitosamente";
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>