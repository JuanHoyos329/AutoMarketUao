const { Router } = require('express');

const { tramiteGet,
    tramitesByIdGet,
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

//Insertar una persona en la tabla de personas
router.post('/',
    tramitePost);

//Eliminar una persona en la tabla de personas
router.delete('/:id',
    UpdatePasos);
module.exports = router;

//Modificar una persona en la tabla de personas
router.put('/cancel/:id',
    CancelTramite);

module.exports = router;