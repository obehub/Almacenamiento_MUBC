// Dependencias: jQuery
$(function(){
    // Búsqueda en cliente
    $('#searchInput').on('input', function(){
        const q = $(this).val().toLowerCase();
        $('#tablaBody tr').each(function(){
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(q) !== -1);
        });
        updatePager();
    });

    // Eliminar con AJAX
    $(document).on('click', '.btn-delete', function(){
        const $btn = $(this);
        const id = $btn.data('id');
        if (!confirm('¿Eliminar este registro? Esta acción no se puede deshacer.')) return;
        $btn.prop('disabled', true).text('Eliminando...');

        $.ajax({
            url: '/PHP/eliminar_registro.php',
            method: 'POST',
            data: { id: id },
            dataType: 'json'
        }).done(function(resp){
            if (resp.success) {
                // quitar fila
                $btn.closest('tr').fadeOut(200, function(){ $(this).remove(); updatePager(); });
            } else {
                alert('No se pudo eliminar: ' + (resp.message || 'error'));
                $btn.prop('disabled', false).text('Eliminar');
            }
        }).fail(function(){
            alert('Error de red al intentar eliminar.');
            $btn.prop('disabled', false).text('Eliminar');
        });
    });

    // Exportar CSV
    $('#exportCsv').on('click', function(){
        const rows = [];
        $('#tablaBody tr:visible').each(function(){
            const cols = [];
            $(this).find('td').each(function(){ cols.push('"' + $(this).text().replace(/"/g,'""') + '"'); });
            rows.push(cols.join(','));
        });
        const csv = rows.join('\n');
        const blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url; a.download = 'inscritos.csv'; document.body.appendChild(a); a.click(); a.remove();
        URL.revokeObjectURL(url);
    });

    // Paginación simple cliente
    const rowsPerPage = 20;
    let currentPage = 1;

    function updatePager(){
        const $visible = $('#tablaBody tr:visible');
        const total = $visible.length;
        const pages = Math.max(1, Math.ceil(total / rowsPerPage));
        if (currentPage > pages) currentPage = pages;

        $visible.hide();
        $visible.slice((currentPage-1)*rowsPerPage, currentPage*rowsPerPage).show();

        $('#pageInfo').text('Página ' + currentPage + ' / ' + pages + ' — ' + total + ' registros');
        $('#prevPage').prop('disabled', currentPage === 1);
        $('#nextPage').prop('disabled', currentPage === pages);
    }

    $('#prevPage').on('click', function(){ currentPage = Math.max(1, currentPage - 1); updatePager(); });
    $('#nextPage').on('click', function(){ currentPage++; updatePager(); });

    // init
    updatePager();
});


$(document).ready(function() {
  let paginaActual = 1;
  let busquedaActual = '';

  function cargarRegistros(pagina = 1, busqueda = '') {
    $.ajax({
      url: `../PHP/tabla.php?pagina=${pagina}&buscar=${busqueda}`,
      method: 'GET',
      dataType: 'json',
      success: function(response) {
        const tbody = $('#tablaRegistros tbody');
        tbody.empty();

        response.data.forEach(fila => {
          const row = `
            <tr>
              <td>${fila.nombre}</td>
              <td>${fila.apellido}</td>
              <td>${fila.cedula}</td>
              <td>${fila.telefono}</td>
              <td>${fila.lugar}</td>
              <td>${fila.mesa}</td>
              <td>${fila.rf_por}</td>
              <td>
                <button class="action-button edit" onclick='editarRegistro(${JSON.stringify(fila)})'><i class="fas fa-edit"></i></button>
                <button class="action-button delete" onclick='eliminarRegistro(${fila.id})'><i class="fas fa-trash"></i></button>
              </td>
            </tr>`;
          tbody.append(row);
        });

        // Paginación
        const paginacion = $('#paginacion');
        paginacion.empty();
        for (let i = 1; i <= response.total_paginas; i++) {
          const btn = $(`<a href="#" class="action-button ${i === pagina ? 'active' : ''}">${i}</a>`);
          btn.click(function(e) {
            e.preventDefault();
            paginaActual = i;
            cargarRegistros(i, busquedaActual);
          });
          paginacion.append(btn);
        }
      }
    });
  }

  $('#formBuscar').submit(function(e) {
    e.preventDefault();
    busquedaActual = $('#buscar').val();
    cargarRegistros(1, busquedaActual);
  });

  $('#btnNuevo').click(mostrarFormularioAgregar);

  // Funciones de SweetAlert
  window.mostrarFormularioAgregar = function() {
    Swal.fire({
      title: 'Nuevo Registro',
      html: `
        <input type="text" id="nombre" class="swal2-input" placeholder="Nombre" required>
        <input type="text" id="apellido" class="swal2-input" placeholder="Apellido" required>
        <input type="text" id="cedula" class="swal2-input" placeholder="Cédula" required>
        <input type="tel" id="telefono" class="swal2-input" placeholder="Teléfono" required>
        <input type="text" id="lugar" class="swal2-input" placeholder="Lugar donde vota" required>
        <input type="text" id="mesa" class="swal2-input" placeholder="Mesa" required>
        <input type="text" id="rf_por" class="swal2-input" placeholder="Referido por">
      `,
      showCancelButton: true,
      confirmButtonText: 'Guardar',
      cancelButtonText: 'Cancelar',
      preConfirm: () => {
        return {
          nombre: $('#nombre').val(),
          apellido: $('#apellido').val(),
          cedula: $('#cedula').val(),
          telefono: $('#telefono').val(),
          lugar: $('#lugar').val(),
          mesa: $('#mesa').val(),
          rf_por: $('#rf_por').val()
        }
      }
    }).then(result => {
      if (result.isConfirmed) {
        $.post('../PHP/registro.php', result.value, function() {
          Swal.fire('Éxito', 'Registro agregado correctamente', 'success')
              .then(() => cargarRegistros());
        });
      }
    });
  }

  window.editarRegistro = function(registro) {
    Swal.fire({
      title: 'Editar Registro',
      html: `
        <input type="text" id="nombre" class="swal2-input" value="${registro.nombre}" required>
        <input type="text" id="apellido" class="swal2-input" value="${registro.apellido}" required>
        <input type="text" id="cedula" class="swal2-input" value="${registro.cedula}" required>
        <input type="tel" id="telefono" class="swal2-input" value="${registro.telefono}" required>
        <input type="text" id="lugar" class="swal2-input" value="${registro.lugar}" required>
        <input type="text" id="mesa" class="swal2-input" value="${registro.mesa}" required>
        <input type="text" id="rf_por" class="swal2-input" value="${registro.rf_por}">
      `,
      showCancelButton: true,
      confirmButtonText: 'Actualizar',
      preConfirm: () => {
        return {
          id: registro.id,
          nombre: $('#nombre').val(),
          apellido: $('#apellido').val(),
          cedula: $('#cedula').val(),
          telefono: $('#telefono').val(),
          lugar: $('#lugar').val(),
          mesa: $('#mesa').val(),
          rf_por: $('#rf_por').val()
        }
      }
    }).then(result => {
      if (result.isConfirmed) {
        $.post('../PHP/editar_registro.php', result.value, function() {
          Swal.fire('Éxito', 'Registro actualizado correctamente', 'success')
              .then(() => cargarRegistros());
        });
      }
    });
  }

  window.eliminarRegistro = function(id) {
    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar'
    }).then(result => {
      if (result.isConfirmed) {
        $.post('../PHP/eliminar_registro.php', { id }, function(response) {
          Swal.fire('Eliminado', 'Registro eliminado correctamente', 'success')
              .then(() => cargarRegistros());
        });
      }
    });
  }

  // Cargar tabla inicial
  cargarRegistros();
});