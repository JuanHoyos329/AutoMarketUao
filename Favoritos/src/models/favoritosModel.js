const mysql = require("mysql2/promise");


// Configuración de la conexión a la base de datos
const connection = mysql.createPool({
    host: "localhost",
    user: "root",
    password: "",
    database: "favoritosDB",
    port: "3307"

});

// Obtener todos los favoritos
async function obtenerFavoritos() {
    const [result] = await connection.query("SELECT * FROM favoritos");
    return result;
}

// Obtener los favoritos de un usuario específico
async function obtenerFavoritosPorUsuario(usuario_id) {
    const [result] = await connection.query(
        "SELECT * FROM favoritos WHERE usuario_id = ?",
        [usuario_id]
    );
    return result;
}

// Agregar un nuevo favorito
async function agregarFavorito(usuario_id, publicacion_id) {
    const [result] = await connection.query(
        "INSERT INTO favoritos (usuario_id, publicacion_id) VALUES (?, ?)",
        [usuario_id, publicacion_id]
    );
    return result;
}

// Eliminar un favorito
async function eliminarFavorito(id) {
    const [result] = await connection.query("DELETE FROM favoritos WHERE id = ?", [id]);
    return result;
}

// obtener favoritos con filtros
async function obtenerFavoritosConFiltros(filtros) {
    let query = "SELECT * FROM favoritos WHERE 1=1"; // 1=1 es para concatenar dinámicamente
    let params = [];

    if (filtros.usuario_id) {
        query += " AND usuario_id = ?";
        params.push(filtros.usuario_id);
    }

    if (filtros.publicacion_id) {
        query += " AND publicacion_id = ?";
        params.push(filtros.publicacion_id);
    }

    if (filtros.fecha_inicio) {
        query += " AND fecha_agregado >= ?";
        params.push(filtros.fecha_inicio);
    }

    if (filtros.fecha_fin) {
        query += " AND fecha_agregado <= ?";
        params.push(filtros.fecha_fin);
    }

    console.log("Consulta SQL:", query);
    console.log("Parámetros:", params);

    const [result] = await connection.query(query, params);
    return result;
}

module.exports = {
    obtenerFavoritos,
    obtenerFavoritosPorUsuario,
    agregarFavorito,
    eliminarFavorito,
    obtenerFavoritosConFiltros 
};


