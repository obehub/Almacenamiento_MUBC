document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('formAdmin');
  const cedulaInput = document.getElementById('cedula');

  // --- Formatear cédula automáticamente ---
  if (cedulaInput) {
    cedulaInput.addEventListener('input', function(e) {
      let value = e.target.value.replace(/[^0-9]/g, '');
      let formatted = '';

      if (value.length > 0) formatted += value.substring(0, 3);
      if (value.length > 3) formatted += '-' + value.substring(3, 10);
      if (value.length > 10) formatted += '-' + value.substring(10, 11);

      e.target.value = formatted;
    });
  }

  // --- Envío del formulario ---
  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const usuario = document.getElementById('usuario').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const cedula = cedulaInput.value.trim();

    const formData = new FormData();
    formData.append('usuario', usuario);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('cedula', cedula);

    // ✅ Ajustar la ruta según tu estructura
    fetch('../PHP/add_admin.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('✅ ' + data.message);
        form.reset();
      } else if (data.message.includes('registro')) {
        alert('⚠️ Esta cédula no existe en la tabla de registro. No puedes agregar este administrador.');
      } else {
        alert('❌ ' + data.message);
      }
    })
    .catch(() => alert('Error al enviar los datos al servidor.'));
  });
});
