const express = require('express');
const router = express.Router();
const favoritosModel = require("../models/favoritosModel");

// Obtener todos los favoritos
router.get("/favoritos", async (req, res) => {
    //try {
        const result = await favoritosModel.obtenerFavoritos();
        res.json(result);
    //} catch (error) {
        //res.status(500).json({ error: "Error al obtener los favoritos" });
    //}
});

// Obtener los favoritos de un usuario específico
router.get("/favoritos/usuario/:id", async (req, res) => {
    //try {
        const { id } = req.params;
        const result = await favoritosModel.obtenerFavoritosPorUsuario(Number(id));
        res.json(result);
    //} catch (error) {
        //res.status(500).json({ error: "Error al obtener los favoritos del usuario" });
    //}
});

// Agregar un nuevo favorito
router.post('/favoritos', async (req, res) => {
    const usuario_id = req.body.usuario_id;
    const publicacion_id = req.body.publicacion_id;

    var result = await favoritosModel.agregarFavorito(usuario_id, publicacion_id);
    res.send("Favorito agregado correctamente");
});


// Eliminar un favorito
router.delete("/favoritos/:id", async (req, res) => {
    try {
        const { id } = req.params;
        const result = await favoritosModel.eliminarFavorito(id);

        if (result.affectedRows === 0) {
            return res.status(404).json({ error: "Favorito no encontrado" });
        }

        res.send("Favorito eliminado correctamente");
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: "Error al eliminar el favorito" });
    }
});

//filtros
router.get("/favoritos/filtrar", async (req, res) => {
    //try {
        const filtros = {
            usuario_id: req.query.usuario_id,
            publicacion_id: req.query.publicacion_id,
            fecha_inicio: req.query.fecha_inicio,
            fecha_fin: req.query.fecha_fin
        };

        const result = await favoritosModel.obtenerFavoritosConFiltros(filtros);
        res.json(result);
    //} catch (error) {
        //console.error(error);
        //res.status(500).json({ error: "Error al filtrar los favoritos" });
    //}
});

module.exports = router;

