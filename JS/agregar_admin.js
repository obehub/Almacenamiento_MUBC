document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formAdmin');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const datos = new FormData(form);

    try {
      const response = await fetch('./PHP/agregar_admin.php', {
        method: 'POST',
        body: datos
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        form.reset();
      } else {
        alert(result.message);
      }

    } catch (error) {
      console.error('Error:', error);
      alert('Hubo un problema al enviar los datos.');
    }
  });
});
