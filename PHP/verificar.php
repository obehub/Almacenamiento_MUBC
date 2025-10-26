<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conexion.php';
    
    // Verificar si se recibió la cédula
    if(isset($_POST['cedula'])) {
        // Obtener y sanitizar la cédula
        $cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
        
        // Preparar y ejecutar la consulta
        $query = "SELECT * FROM registro WHERE cedula = '$cedula'";
        $resultado = mysqli_query($conexion, $query);
        
        // Preparar la respuesta
        $response = array();
        
        if(mysqli_num_rows($resultado) > 0) {
            // La cédula existe
            $response['existe'] = true;
            $response['mensaje'] = "La cédula ya está registrada en el MUBC.";
        } else {
            // La cédula no existe
            $response['existe'] = false;
            $response['mensaje'] = "La cédula no está registrada en el MUBC.";
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