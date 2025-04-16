const express = require('express');
const router = express.Router();
const favoritosController = require('../controllers/favoritosController');

// Agregar favorito
router.post('/', favoritosController.agregarFavorito);

// Eliminar favorito
router.delete('/:id', favoritosController.eliminarFavorito);

// Verificar favorito 
router.get('/:userId/:publicacionId', favoritosController.verificarFavorito);

// Obtener favoritos de un usuario 
router.get('/:userId', favoritosController.obtenerFavoritos);

module.exports = router;

