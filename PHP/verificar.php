<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conn.php';
    
    if (isset($_POST['cedula'])) {
        $cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
        $response = array();

        // --- Verificar si está en la tabla de administradores ---
        $query_admin = "SELECT * FROM administradores WHERE cedula = '$cedula' LIMIT 1";
        $resultado_admin = mysqli_query($conexion, $query_admin);

        if (mysqli_num_rows($resultado_admin) > 0) {
            // Si es administrador
            $response['admin'] = true;
            $response['mensaje'] = "Redirigiendo al acceso de administrador...";
            $response['redirect'] = 'login_admin.html';
        } else {
            // Verificar si está en la tabla de registro normal
            $query_registro = "SELECT * FROM registro WHERE cedula = '$cedula' LIMIT 1";
            $resultado_registro = mysqli_query($conexion, $query_registro);

            if (mysqli_num_rows($resultado_registro) > 0) {
                $response['existe'] = true;
                $response['mensaje'] = "La cédula ya está registrada en el MUBC.";
            } else {
                $response['existe'] = false;
                $response['mensaje'] = "La cédula no está registrada en el MUBC.";
            }
        }

        echo json_encode($response);
    } else {
        echo json_encode([
            'error' => true,
            'mensaje' => 'No se recibió ninguna cédula para verificar'
        ]);
    }

    mysqli_close($conexion);
}
?>

