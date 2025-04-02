const mysql = require("mysql2/promise");


// Configuración de la conexión a la base de datos
const connection = mysql.createPool({
    host: "localhost",
    user: "root",
    password: "root",
    database: "favoritos",
    port: "3306"

});

// Obtener todos los favoritos
async function obtenerFavoritos() {
    const [result] = await connection.query("SELECT * FROM favoritos");
    return result;
}

// Obtener los favoritos de un usuario específico
async function obtenerFavoritosPorUsuario(userId) {
    const [result] = await connection.query(
        "SELECT * FROM favoritos WHERE userId = ?",
        [userId]
    );
    return result;
}

// Agregar un nuevo favorito
async function agregarFavorito(userId, idPublicacion) {
    const [result] = await connection.query(
        "INSERT INTO favoritos (userId, idPublicacion) VALUES (?, ?)",
        [userId, idPublicacion]
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

    if (filtros.userId) {
        query += " AND userId = ?";
        params.push(filtros.userId);
    }

    if (filtros.idPublicacion) {
        query += " AND idPublicacion = ?";
        params.push(filtros.idPublicacion);
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


