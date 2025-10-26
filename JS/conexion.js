import { neon } from '@netlify/neon';

const sql = neon('postgresql://neondb_owner:npg_iQJB0qYwU4RC@ep-crimson-mode-ae1rzfg5-pooler.c-2.us-east-2.aws.neon.tech/neondb?sslmode=require&channel_binding=require');

async function probarConexion() {
  try {
    const result = await sql`SELECT NOW()`;
    console.log('✅ Conexión exitosa:', result);
  } catch (err) {
    console.error('❌ Error en la conexión:', err);
  }
}

probarConexion();
// Asegúrate de reemplazar 'usuario', 'contraseña' y la URL con tus credenciales reales de Neon.

// Cierra la conexión cuando ya no sea necesaria
 sql.end(); // Descomenta esto si deseas cerrar la conexión en algún momento específico