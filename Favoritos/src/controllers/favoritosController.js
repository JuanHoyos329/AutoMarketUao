const axios = require('axios');
const mysql = require("../db/mysql");

// URL base del microservicio de publicaciones
const PUBLICACIONES_SERVICE_URL = process.env.PUBLICACIONES_SERVICE_URL || "http://192.168.100.3:8080"; 

const agregarFavorito = async (req, res) => {
    const { userId, idPublicacion } = req.body;

    try {
        // Log para depuración
        const url = `${PUBLICACIONES_SERVICE_URL}/automarket/publicaciones/${idPublicacion}`;
        console.log(`Consultando publicación en: ${url}`);

        // 1. Llamar al microservicio de publicaciones para validar y obtener datos
        const pubResponse = await axios.get(url);
        if (!pubResponse.data || !pubResponse.data.idPublicacion) {
            return res.status(404).json({ message: "La publicación no existe" });
        }
        const publicacion = pubResponse.data;

        // 2. Extraer los datos necesarios de la publicación
        const { marca, modelo, ano, precio, kilometraje } = publicacion;

        // 3. Insertar en la base de datos de favoritos (sin fechaPublicacion)
        const query = `INSERT INTO favoritos (userId, idPublicacion, marca, modelo, ano, precio, kilometraje) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)`;
        const [result] = await mysql.execute(query, [
            userId,
            idPublicacion,
            marca || null,
            modelo || null,
            ano || null,
            precio || null,
            kilometraje || null
        ]);
        res.status(201).json({ message: "Favorito agregado exitosamente", data: result });
    } catch (error) {
        if (error.response && error.response.status === 404) {
            return res.status(404).json({ message: "La publicación no existe" });
        }
        console.error(error);
        res.status(500).json({ message: "Error al agregar el favorito" });
    }
};

const eliminarFavorito = async (req, res) => {
    const { id } = req.params;
    try {
        const query = `DELETE FROM favoritos WHERE id = ?`;
        const [result] = await mysql.execute(query, [id]);
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: "Favorito no encontrado" });
        }
        res.status(200).json({ message: "Favorito eliminado exitosamente" });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: "Error al eliminar el favorito" });
    }
};

const obtenerFavoritos = async (req, res) => {
    const { userId } = req.params;
    try {
        const query = `SELECT * FROM favoritos WHERE userId = ?`;
        const [result] = await mysql.execute(query, [userId]);
        if (result.length === 0) {
            return res.status(404).json({ message: "No se encontraron favoritos" });
        }
        res.status(200).json({ data: result });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: "Error al obtener los favoritos" });
    }
};

const verificarFavorito = async (req, res) => {
    const { userId, publicacionId } = req.params;
    try {
        const query = `SELECT * FROM favoritos WHERE userId = ? AND idPublicacion = ?`;
        const [result] = await mysql.execute(query, [userId, publicacionId]);
        if (result.length === 0) {
            return res.status(404).json({ message: "El favorito no existe" });
        }
        res.status(200).json({ message: "El favorito existe", data: result });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: "Error al verificar el favorito" });
    }
};

module.exports = {
    agregarFavorito,
    eliminarFavorito,
    obtenerFavoritos,
    verificarFavorito
};
