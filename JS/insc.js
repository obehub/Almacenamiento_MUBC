 const input = document.getElementById('inputFormato');

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

    const telefonoInput = document.getElementById('teleFormato');
    telefonoInput.addEventListener('input', function(e) {
        let valor = e.target.value.replace(/[^0-9]/g, ''); // Elimina todo lo que no sea número
        let formato = '';
        // Aplica el formato XXX-XXX-XXXX
        if (valor.length > 0) {
            formato += valor.substring(0, 3); // Primeros 3 dígitos
        }
        if (valor.length > 3) {
            formato += '-' + valor.substring(3, 6); // Guion y siguientes 3 dígitos
        }
        if (valor.length > 6) {
            formato += '-' + valor.substring(6, 10); // Guion y últimos 4 dígitos
        }
        
        e.target.value = formato;
    });