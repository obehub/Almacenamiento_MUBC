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
            url: '/Almacenamiento_MUBC/PHP/eliminar_registro.php',
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
