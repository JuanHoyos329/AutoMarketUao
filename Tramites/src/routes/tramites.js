const { Router } = require('express');

const { tramiteGet,
    tramitesByIdGet,
    tramitesByCompradorGet,
    tramitesByVendedorGet,
    tramitePost,
    UpdatePasos,
    CancelTramite
} = require('../controllers/tramites');

const router = Router();

//Seleccionar todas las personas
router.get('/',
    tramiteGet);

//Seleccionar una persona por id
router.get('/:id',
    tramitesByIdGet);

//Seleccionar una persona por id
router.get('/comprador/:id',
    tramitesByCompradorGet);

//Seleccionar una persona por id
router.get('/vendedor/:id',
    tramitesByVendedorGet);

//Insertar una persona en la tabla de personas
router.post('/',
    tramitePost);

//Eliminar una persona en la tabla de personas
router.put('/paso/:id',
    UpdatePasos);
module.exports = router;

//Modificar una persona en la tabla de personas
router.put('/cancel/:id',
    CancelTramite);

module.exports = router;