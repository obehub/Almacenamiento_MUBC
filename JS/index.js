$(document).ready(function() {
  // --- Menú hamburguesa para móviles ---
  $(".menu-toggle").click(function() {
    $(".menu").toggleClass("active");
  });

  // --- Mostrar alerta si viene ?inscrito=1 en la URL ---
  const params = new URLSearchParams(window.location.search);
  if (params.get('inscrito') === '1') {
    alert('¡Registro exitoso! Ya estás inscrito en el MUBC.');
    if (window.history.replaceState) {
      const url = new URL(window.location);
      url.searchParams.delete('inscrito');
      window.history.replaceState({}, document.title, url.pathname + url.search);
    }
  }

  // --- URL base del backend en Railway ---
  const API_URL = "https://bd-production-38ba.up.railway.app/";


  // --- Handler AJAX para verificar cédula ---
  $('#formVerificar').on('submit', function(e) {
    e.preventDefault();

    const cedula = $('#cedula').val().trim();
    if (cedula === '') {
      $('#mensaje').text('Por favor, ingrese una cédula.').css('color', 'red');
      return;
    }

    $.ajax({
      url: `${API_URL}/verificar.php`, // 🔹 apunta a tu backend en Railway
      method: 'POST',
      dataType: 'json',
      data: { cedula },
      success: function(response) {
        if (response.admin) {
          $('#mensaje').text('Redirigiendo al acceso de administrador...').css('color', 'blue');
          setTimeout(() => {
            window.location.href = response.redirect || 'login_admin.html';
          }, 1000);
        } else if (response.existe) {
          $('#mensaje').text(response.mensaje).css('color', 'green');
        } else if (response.error) {
          $('#mensaje').text(response.mensaje).css('color', 'red');
        } else {
          $('#mensaje').text(response.mensaje).css('color', 'red');
        }
      },
      error: function(xhr, status, error) {
        console.error("Error:", status, error);
        $('#mensaje').text('Error al verificar la cédula.').css('color', 'red');
      }
    });
  });

  // --- Formateo automático de cédula ---
  const input = document.getElementById('cedula');
  if (input) {
    input.addEventListener('input', function(e) {
      let valor = e.target.value.replace(/[^0-9]/g, '');
      let formato = '';
      if (valor.length > 0) formato += valor.substring(0, 3);
      if (valor.length > 3) formato += '-' + valor.substring(3, 10);
      if (valor.length > 10) formato += '-' + valor.substring(10, 11);
      e.target.value = formato;
    });
  }
});
