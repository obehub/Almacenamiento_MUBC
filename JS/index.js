$(document).ready(function() {
  // --- Menú hamburguesa para móviles ---
  $(".menu-toggle").click(function() {
    $(".menu").toggleClass("active");
  });

  // --- Mostrar alerta si viene ?inscrito=1 en la URL ---
  const params = new URLSearchParams(window.location.search);
  if (params.get('inscrito') === '1') {
    alert('¡Registro exitoso! Ya estás inscrito en el MUBC.');
    // Quitar el parámetro de la URL
    if (window.history.replaceState) {
      const url = new URL(window.location);
      url.searchParams.delete('inscrito');
      window.history.replaceState({}, document.title, url.pathname + url.search);
    }
  }

  // --- Handler AJAX para verificar cédula ---
  $('#formVerificar').on('submit', function(e) {
    e.preventDefault();

    const cedula = $('#cedula').val().trim();
    if (cedula === '') {
      $('#mensaje').text('Por favor, ingrese una cédula.').css('color', 'red');
      return;
    }

    $.ajax({
      url: '/Almacenamiento_MUBC/PHP/verificar.php',
      method: 'POST',
      dataType: 'json',
      data: { cedula },
      success: function(response) {
        if (response.admin) {
          // 🔹 Si es administrador, redirigir al login de admin
          $('#mensaje').text('Redirigiendo al acceso de administrador...').css('color', 'blue');
          setTimeout(() => {
            window.location.href = response.redirect || 'login_admin.html';
          }, 1000);
        } 
        else if (response.existe) {
          // 🔹 Cédula registrada
          $('#mensaje').text(response.mensaje).css('color', 'green');
        } 
        else if (response.error) {
          // 🔹 Error desde el servidor
          $('#mensaje').text(response.mensaje).css('color', 'red');
        } 
        else {
          // 🔹 No registrada
          $('#mensaje').text(response.mensaje).css('color', 'red');
        }
      },
      error: function() {
        $('#mensaje').text('Error al verificar la cédula.').css('color', 'red');
      }
    });
  });

  // --- Formateo automático de cédula: XXX-XXXXXXX-X ---
  const input = document.getElementById('cedula');
  if (input) {
    input.addEventListener('input', function(e) {
      let valor = e.target.value.replace(/[^0-9]/g, ''); // Quita todo excepto números
      let formato = '';

      if (valor.length > 0) formato += valor.substring(0, 3);
      if (valor.length > 3) formato += '-' + valor.substring(3, 10);
      if (valor.length > 10) formato += '-' + valor.substring(10, 11);

      e.target.value = formato;
    });
  }
});
