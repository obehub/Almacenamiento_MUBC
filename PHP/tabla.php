<?php
require 'conexion.php';

// Configuración de paginación
$registros_por_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $registros_por_pagina;

// Búsqueda
$busqueda = isset($_GET['buscar']) ? mysqli_real_escape_string($conexion, $_GET['buscar']) : '';
$where = '';
if (!empty($busqueda)) {
    $where = "WHERE nombre LIKE '%$busqueda%' OR 
              apellido LIKE '%$busqueda%' OR 
              cedula LIKE '%$busqueda%' OR 
              telefono LIKE '%$busqueda%'";
}

// Contar total de registros para paginación
$sql_total = "SELECT COUNT(*) as total FROM registro $where";
$result_total = mysqli_query($conexion, $sql_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_registros = $row_total['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Personas - MUBC</title>
    <link rel="stylesheet" href="../CSS/tabla.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <nav class="nav-container">
            <img src="../IMAGENES/MUBC.JPG" alt="Logo MUBC" class="logo">
            <label for="text">Movimiento Union y Bienestar Ciudadano</label>
            <ul class="nav-links">
                <li><a href="../admin.html"><i class="fas fa-home"></i> Inicio</a></li>
                <li><a href="tabla.php"><i class="fas fa-users"></i> Inscritos</a></li>
                <li><a href="../agregar_admin.html"><i class="fas fa-user-plus"></i> Agregar administrador</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
    <div class="container">
        <h1>Registro de Personas en el MUBC</h1>

        <div class="table-controls">
            <div class="control-group">
                <form action="" method="GET" style="display: flex; gap: 10px;">
                    <input type="text" name="buscar" class="search-box" 
                           placeholder="Buscar por nombre, cédula..." 
                           value="<?php echo htmlspecialchars($busqueda); ?>">
                    <button type="submit" class="action-button">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </form>
            </div>
            <div class="control-group">
                <button class="action-button success" onclick="mostrarFormularioAgregar()">
                    <i class="fas fa-plus"></i> Nuevo Registro
                </button>
                <a href="/Almacenamiento_MUBC/admin.html" class="action-button">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Cédula</th>
                        <th>Teléfono</th>
                        <th>Lugar donde votas</th>
                        <th>Mesa</th>
                        <th>Referido por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $consulta = "SELECT * FROM registro $where 
                                LIMIT $inicio, $registros_por_pagina";
                    $resultado = mysqli_query($conexion, $consulta);

                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        // Normalizar id para JavaScript
                        $fila['id'] = isset($fila['id']) ? $fila['id'] : 
                                    (isset($fila['id_registro']) ? $fila['id_registro'] : null);

                        echo "<tr>";
                        echo "<td><div class='cell-content'>" . htmlspecialchars($fila['nombre']) . "</div></td>";
                        echo "<td><div class='cell-content'>" . htmlspecialchars($fila['apellido']) . "</div></td>";
                        echo "<td><div class='cell-content'>" . htmlspecialchars($fila['cedula']) . "</div></td>";
                        echo "<td><div class='cell-content'>" . htmlspecialchars($fila['telefono']) . "</div></td>";
                        echo "<td><div class='cell-content'>" . htmlspecialchars($fila['lugar']) . "</div></td>";
                        echo "<td><div class='cell-content'>" . htmlspecialchars($fila['mesa']) . "</div></td>";
                        echo "<td><div class='cell-content'>" . htmlspecialchars($fila['rf_por']) . "</div></td>";
                        echo "<td class='acciones'>";
                        echo "<button onclick='editarRegistro(" . json_encode($fila) . ")' class='action-button edit' title='Editar'><i class='fas fa-edit'></i></button>";
                        echo "<button onclick='eliminarRegistro(" . $fila['id'] . ")' class='action-button delete' title='Eliminar'><i class='fas fa-trash'></i></button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_paginas > 1): ?>
        <div class="pagination">
            <?php if ($pagina > 1): ?>
                <a href="?pagina=1<?php echo !empty($busqueda) ? '&buscar='.urlencode($busqueda) : ''; ?>" 
                   class="action-button" title="Primera página">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            <?php endif; ?>

            <?php
            $rango = 2;
            for ($i = max(1, $pagina - $rango); $i <= min($total_paginas, $pagina + $rango); $i++): 
            ?>
                <a href="?pagina=<?php echo $i; ?><?php echo !empty($busqueda) ? '&buscar='.urlencode($busqueda) : ''; ?>" 
                   class="action-button <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($pagina < $total_paginas): ?>
                <a href="?pagina=<?php echo $total_paginas; ?><?php echo !empty($busqueda) ? '&buscar='.urlencode($busqueda) : ''; ?>" 
                   class="action-button" title="Última página">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    </main>

            <footer>
        <div class="footer-container">
            <!-- Información de contacto -->
            <section class="contact-info">
                <h3>Información de Contacto</h3>
                <ul class="contact-list">
                    <li>Teléfono: +1 829 898 0712</li>
                    <li>Email: chavezobed35@gmail.com</li>
                </ul>
            </section>

            <!-- Redes sociales -->
            <section class="social-links">
                <h3>Redes Sociales</h3>
                <ul class="social-list">
                    <li><a href="#" target="_blank"><i class="fab fa-facebook"></i> Facebook</a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-twitter"></i> Twitter</a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-instagram"></i> Instagram</a></li>
                    <li><a href="#" target="_blank"><i class="fab fa-youtube"></i> YouTube</a></li>
                </ul>
            </section>

            <!-- Copyright -->
            <section class="copyright">
                <p>&copy; 2025 Movimiento Union y Bienestar Ciudadano. Todos los derechos reservados.</p>
            </section>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    function mostrarFormularioAgregar() {
        Swal.fire({
            title: 'Nuevo Registro',
            html: `
                <form id="formAgregar" class="form-sweet">
                    <input type="text" id="nombre" class="swal2-input" placeholder="Nombre" required>
                    <input type="text" id="apellido" class="swal2-input" placeholder="Apellido" required>
                    <input type="text" id="cedula" class="swal2-input" placeholder="Cédula" required pattern="[0-9]{3}-[0-9]{7}-[0-9]{1}">
                    <input type="tel" id="telefono" class="swal2-input" placeholder="Teléfono" required>
                    <input type="text" id="lugar" class="swal2-input" placeholder="Lugar donde vota" required>
                    <input type="text" id="mesa" class="swal2-input" placeholder="Mesa" required>
                    <input type="text" id="rf_por" class="swal2-input" placeholder="Referido por">
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                return {
                    nombre: document.getElementById('nombre').value,
                    apellido: document.getElementById('apellido').value,
                    cedula: document.getElementById('cedula').value,
                    telefono: document.getElementById('telefono').value,
                    lugar: document.getElementById('lugar').value,
                    mesa: document.getElementById('mesa').value,
                    rf_por: document.getElementById('rf_por').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'registro.php',
                    method: 'POST',
                    data: result.value,
                    success: function(response) {
                        Swal.fire('¡Éxito!', 'Registro agregado correctamente', 'success')
                        .then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo agregar el registro', 'error');
                    }
                });
            }
        });
    }

    function editarRegistro(registro) {
        Swal.fire({
            title: 'Editar Registro',
            html: `
                <form id="formEditar" class="form-sweet">
                    <input type="text" id="nombre" class="swal2-input" value="${registro.nombre}" required>
                    <input type="text" id="apellido" class="swal2-input" value="${registro.apellido}" required>
                    <input type="text" id="cedula" class="swal2-input" value="${registro.cedula}" required>
                    <input type="tel" id="telefono" class="swal2-input" value="${registro.telefono}" required>
                    <input type="text" id="lugar" class="swal2-input" value="${registro.lugar}" required>
                    <input type="text" id="mesa" class="swal2-input" value="${registro.mesa}" required>
                    <input type="text" id="rf_por" class="swal2-input" value="${registro.rf_por}">
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Actualizar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                return {
                    id: registro.id,
                    nombre: document.getElementById('nombre').value,
                    apellido: document.getElementById('apellido').value,
                    cedula: document.getElementById('cedula').value,
                    telefono: document.getElementById('telefono').value,
                    lugar: document.getElementById('lugar').value,
                    mesa: document.getElementById('mesa').value,
                    rf_por: document.getElementById('rf_por').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'editar_registro.php',
                    method: 'POST',
                    data: result.value,
                    success: function(response) {
                        Swal.fire('¡Éxito!', 'Registro actualizado correctamente', 'success')
                        .then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo actualizar el registro', 'error');
                    }
                });
            }
        });
    }

    function eliminarRegistro(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'eliminar_registro.php',
                    method: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('¡Eliminado!', response.message, 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Error', response.message || 'No se pudo eliminar el registro', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                    }
                });
            }
        });
    }
    </script>
</body>
</html>
<?php mysqli_close($conexion); ?>