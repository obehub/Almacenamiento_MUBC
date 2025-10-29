<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   require 'conn.php';

   // Obtener y sanitizar entradas
   $nombre   = isset($_POST['nombre']) ? mysqli_real_escape_string($conexion, $_POST['nombre']) : '';
   $apellido = isset($_POST['apellido']) ? mysqli_real_escape_string($conexion, $_POST['apellido']) : '';
   $cedula   = isset($_POST['cedula']) ? mysqli_real_escape_string($conexion, $_POST['cedula']) : '';
   $telefono = isset($_POST['telefono']) ? mysqli_real_escape_string($conexion, $_POST['telefono']) : '';
   $lugar    = isset($_POST['lugar']) ? mysqli_real_escape_string($conexion, $_POST['lugar']) : '';
   $mesa     = isset($_POST['mesa']) ? mysqli_real_escape_string($conexion, $_POST['mesa']) : '';
   $referido = isset($_POST['rf_por']) ? mysqli_real_escape_string($conexion, $_POST['rf_por']) : '';

   // Insert usando sentencia preparada para mayor seguridad
   $stmt = mysqli_prepare($conexion, "INSERT INTO registro (nombre, apellido, cedula, telefono, lugar, mesa, rf_por, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
   if ($stmt) {
      mysqli_stmt_bind_param($stmt, 'sssssss', $nombre, $apellido, $cedula, $telefono, $lugar, $mesa, $referido);
      if (mysqli_stmt_execute($stmt)) {
         // Redirigir al index con flag para mostrar alerta
         header('Location: ../index.html?inscrito=1');
         exit;
      } else {
         // Error en ejecución
         echo 'Error al insertar: ' . mysqli_stmt_error($stmt);
      }
      mysqli_stmt_close($stmt);
   } else {
      echo 'Error en la preparación de la consulta: ' . mysqli_error($conexion);
   }

   mysqli_close($conexion);

}
?>
