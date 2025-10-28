<?php 
header('Access-Control-Allow-Origin: https://mubc2026.netlify.app'); 

// Necesitas esto si usas peticiones POST (como tu AJAX)
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conn.php';
    
    // Verificar si se recibió la cédula
    if(isset($_POST['cedula'])) {
        // Obtener y sanitizar la cédula
        $cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
        
        // Crear respuesta base
        $response = array();

        // --- 1️⃣ Verificar si la cédula está en la tabla de administradores ---
        $query_admin = "SELECT * FROM administradores WHERE cedula = '$cedula' LIMIT 1";
        $resultado_admin = mysqli_query($conexion, $query_admin);

        if (mysqli_num_rows($resultado_admin) > 0) {
            // Si es administrador, redirigir al login de admin
            $response['admin'] = true;
            $response['mensaje'] = "Redirigiendo al acceso de administrador...";
            $response['redirect'] = 'login_admin.html';
        } else {
            // --- 2️⃣ Si no es admin, verificar si está en la tabla de registro ---
            $query_registro = "SELECT * FROM registro WHERE cedula = '$cedula' LIMIT 1";
            $resultado_registro = mysqli_query($conexion, $query_registro);

            if (mysqli_num_rows($resultado_registro) > 0) {
                // La cédula existe en registro
                $response['existe'] = true;
                $response['mensaje'] = "La cédula ya está registrada en el MUBC.";
            } else {
                // La cédula no existe en ninguna tabla
                $response['existe'] = false;
                $response['mensaje'] = "La cédula no está registrada en el MUBC.";
            }
        }

        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Error: no se recibió cédula
        $response = array(
            'error' => true,
            'mensaje' => 'No se recibió ninguna cédula para verificar'
        );
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    mysqli_close($conexion);
}
?>
