$(document).ready(function() {
  // Mostrar alerta si viene ?inscrito=1 en la URL
  // Menú hamburguesa para móviles
  $(".menu-toggle").click(function(){
    $(".menu").toggleClass("active");
  });
  const params = new URLSearchParams(window.location.search);
  if (params.get('inscrito') === '1') {
    alert('¡Registro exitoso! Ya estás inscrito en el MUBC.');
    // Opcional: quitar el parámetro de la URL para evitar que vuelva a mostrarse
    if (window.history.replaceState) {
      const url = new URL(window.location);
      url.searchParams.delete('inscrito');
      window.history.replaceState({}, document.title, url.pathname + url.search);
    }
  }
  
  // Formateo de cédula: XXX-XXXXXXX-X
  const input = document.getElementById('cedula');
  if (input) {
    input.addEventListener('input', function(e) {
      let valor = e.target.value.replace(/[^0-9]/g, ''); // 1. Elimina todo lo que no sea número
      let formato = '';

      // 2. Aplica el formato XXX-XXXXXXX-X
      if (valor.length > 0) {
        formato += valor.substring(0, 3); // Primeros 3 dígitos
      }
      if (valor.length > 3) {
        formato += '-' + valor.substring(3, 10); // Guion y siguientes 7 dígitos
      }
      if (valor.length > 10) {
        formato += '-' + valor.substring(10, 11); // Guion y último dígito
      }

      // 3. Establece el valor formateado
      e.target.value = formato;
    });
  }
});