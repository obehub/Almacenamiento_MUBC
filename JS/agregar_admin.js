document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const usuario = document.getElementById('usuario').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const formData = new FormData();
        formData.append('usuario', usuario);
        formData.append('email', email);
        formData.append('password', password);
        fetch('/Almacenamiento_MUBC/PHP/add_admin.php', {
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
