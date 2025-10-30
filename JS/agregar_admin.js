document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    // NUEVO: Obtener la referencia al campo de cédula
    const cedulaInput = document.getElementById('cedula'); 

    // --- 1. Función para formatear la cédula automáticamente (xxx-xxxxxxx-x) ---
    if (cedulaInput) {
        cedulaInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, ''); // Limpiar el valor, dejando solo dígitos
            let formattedValue = '';

            // Aplicar el formato: xxx-xxxxxxx-x
            if (value.length > 0) {
                // Primer guion después del tercer dígito
                formattedValue += value.substring(0, 3);
            }
            if (value.length > 3) {
                formattedValue += '-' + value.substring(3, 10);
            }
            if (value.length > 10) {
                formattedValue += '-' + value.substring(10, 11);
            }

            e.target.value = formattedValue;
        });
        
        // Opcional: Limitar la longitud máxima (13 caracteres para el patrón con guiones)
        cedulaInput.setAttribute('maxlength', '13');
    }
    // ------------------------------------------------------------------------

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const usuario = document.getElementById('usuario').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        // NUEVO: Obtener el valor del campo de cédula (ya estará formateado)
        const cedula = cedulaInput ? cedulaInput.value : ''; 

        const formData = new FormData();
        formData.append('usuario', usuario);
        formData.append('email', email);
        formData.append('password', password);
        // NUEVO: Añadir la cédula al FormData
        formData.append('cedula', cedula); 

        fetch('../PHP/add_admin.php', {
    method: 'POST',
    body: formData
})

        .then(response => response.json())
        .then(data => {
            if(data.success){
                alert('Administrador agregado correctamente');
                form.reset();
            }else{
                alert('Error: ' + (data.message || 'No se pudo agregar el administrador.'));
            }
        })
        .catch(error => {
            alert('Error en la solicitud');
        });
    });
});