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
  
  // --- Handler AJAX para verificar cédula ---
  $('#formVerificar').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      url: './PHP/verificar.php',
      method: 'POST',
      data: {
        cedula: $('#cedula').val()
      },
      success: function(response) {
        $('#mensaje').text(response.mensaje);
        $('#mensaje').css('color', response.existe ? 'green' : 'red');
      },
      error: function() {
        $('#mensaje').text('Error al verificar la cédula');
        $('#mensaje').css('color', 'red');
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
