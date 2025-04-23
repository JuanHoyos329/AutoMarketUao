//Aqui esta la logica de las consultas

const { response, request } = require('express')
const { Tramites } = require('../models/tramites.model');
const axios = require('axios');

const { bdmysql } = require('../database/MariaDbConnection');


//Traer todas las personas
const tramiteGet = async (req, res = response) => {

    
    const query = req.query;

    //Desestructuracion de argumentos
    const { q, nombre = 'No name', apikey, page = 1, limit = 10 } = req.query;

    console.log("DATOS",q,nombre);
    console.log("QUERY",query);

    try {
        const unosTramites = await Tramites.findAll();

        res.json({
            ok: true,
            msg: 'get API - Controller Funciono',
            query,
            q,
            nombre,
            apikey,
            page,
            limit,
            data: unosTramites
        })

    } catch (error) {
        console.log(error);
        res.status(500).json({
            ok: false,
            msg: 'Hable con el Administrador',
            err: error
        })

    }
    
}

//Traer personas por id
const tramitesByIdGet = async (req = request, res = response) => {

    const { id } = req.params;
    //const { _id, password, google, correo, ...resto } = req.body;

    try {

        const unTramite = await Tramites.findByPk(id);

        if (!unTramite) {
            return res.status(404).json({
                ok: false,
                msg: 'No existe un Tramite con el id: ' + id
            })
        }

        res.json({
            ok: true,
            data: unTramite
        });


    } catch (error) {
        console.log(error);
        res.status(500).json({
            ok: false,
            msg: 'Hable con el Administrador',
            err: error
        })

    }
}

const tramitesByCompradorGet = async (req = request, res = response) => {
    const { id } = req.params;
    console.log(`🔍 Buscando trámites para comprador con ID: ${id}`);

    try {
        const tramites = await Tramites.findAll({ where: { id_comprador: id } });

        console.log("📊 Resultado de la consulta:", tramites);

        if (!tramites || tramites.length === 0) {
            return res.status(200).json({
                ok: true, // Respuesta válida, sin error
                msg: "No tienes trámites para esta categoría",
                data: [] // Devolvemos un array vacío en lugar de null
            });
        }

        res.json({
            ok: true,
            data: tramites
        });

    } catch (error) {
        console.error("❌ Error en la consulta:", error);
        res.status(500).json({
            ok: false,
            msg: "Error al obtener los trámites del comprador",
            err: error.message // Se envía solo el mensaje del error
        });
    }
};

// Obtener trámites por id_vendedor
const tramitesByVendedorGet = async (req = request, res = response) => {
    const { id } = req.params;
    console.log(`🔍 Buscando trámites para vendedor con ID: ${id}`);

    try {
        const tramites = await Tramites.findAll({ where: { id_vendedor: id } });

        console.log("📊 Resultado de la consulta:", tramites);

        if (!tramites || tramites.length === 0) {
            return res.status(200).json({
                ok: true, // Se mantiene true para indicar que la respuesta es válida
                msg: "No tienes trámites para esta categoría",
                data: [] // Se devuelve un array vacío en lugar de null
            });
        }

        res.json({
            ok: true,
            data: tramites
        });

    } catch (error) {
        console.error("❌ Error en la consulta:", error);
        res.status(500).json({
            ok: false,
            msg: "Error al obtener los trámites del vendedor",
            err: error.message // Enviamos solo el mensaje del error para evitar detalles sensibles
        });
    }
};

//Insertar personas
const tramitePost = async (req, res = response) => {
    try {
        const { idPublicacion, idComprador } = req.body;
        
        // Verificar si el usuario ya tiene un trámite activo para esta publicación
        const tramiteExistente = await Tramites.findOne({
            where: {
                id_comprador: idComprador,
                id_vehiculo: idPublicacion,
                estado: "activo"
            }
        });

        if (tramiteExistente) {
            return res.status(400).json({ mensaje: "Ya tienes un tramite activo para esta publicación. Revisa en 'Tramites como Comprador'." });
        }

        // 1. Obtener el usuario que creó la publicación (userId)
        const publicacionResponse = await axios.get(`http://192.168.100.2:8080/automarket/publicaciones/${idPublicacion}`);
        const idVendedor = publicacionResponse.data.userId;

        // 2. Obtener datos del vendedor
        const vendedorResponse = await axios.get(`http://192.168.100.2:8081/automarketuao/users/read/id/${idVendedor}`);
        const { name, last_name, phone, email } = vendedorResponse.data;

        // 3. Obtener datos del comprador
        const compradorResponse = await axios.get(`http://192.168.100.2:8081/automarketuao/users/read/id/${idComprador}`);
        const { name: nameComprador, last_name: lastNameComprador, phone: phoneComprador, email: emailComprador } = compradorResponse.data;

        // 4. Obtener información del vehículo desde la publicación
        const { marca, modelo, ano, precio } = publicacionResponse.data;

        // 5. Crear el trámite en la BD
        const nuevoTramite = await Tramites.create({
            id_vendedor: idVendedor,
            user_vendedor: `${name} ${last_name}`,
            tel_vendedor: phone,
            email_vendedor: email,
            id_comprador: idComprador,
            user_comprador: `${nameComprador} ${lastNameComprador}`,
            tel_comprador: phoneComprador,
            email_comprador: emailComprador,
            id_vehiculo: idPublicacion,
            marca,
            modelo,
            ano,
            precio,
            fecha_inicio: new Date(),
            revision_doc: false,
            cita: false,
            contrato: false,
            pago: false,
            Traspaso: false,
            entrega: false,
            fecha_fin: null,
            estado: "activo"
        });

        // Respuesta con el nuevo trámite
        res.status(201).json({ mensaje: 'Trámite creado exitosamente. Revisa en "Tramites como Comprador"', tramite: nuevoTramite });
    } catch (error) {
        console.error('Error al crear el trámite:', error);
        res.status(500).json({ mensaje: 'Error interno al crear el trámite', error: error.message });
    }
};


// Ruta para actualizar un trámite
const UpdatePasos = async (req, res = response) => {
    try {
        const id = Number(req.params.id);
        const { pasoActualizar, id_usuario } = req.body;

        // Lista de pasos en orden
        const pasosTramite = ["revision_doc", "cita", "contrato", "pago", "Traspaso", "entrega"];

        // Buscar el trámite
        const tramite = await Tramites.findByPk(id);
        if (!tramite) {
            return res.status(404).json({ message: "Trámite no encontrado" });
        }

        // Verificar si el usuario es el vendedor (único que puede actualizar)
        if (Number(tramite.id_vendedor) !== Number(id_usuario)) {
            return res.status(403).json({ message: "No tienes permiso para actualizar este trámite" });
        }

        // Verificar que el paso existe y que se actualiza en el orden correcto
        const indexPaso = pasosTramite.indexOf(pasoActualizar);
        if (indexPaso === -1) {
            return res.status(400).json({ message: "Paso inválido" });
        }

        // Verificar si el paso ya fue completado
        if (tramite[pasoActualizar]) {
            return res.status(400).json({ message: "Este paso ya fue completado" });
        }

        // Verificar si los pasos anteriores ya fueron completados
        if (indexPaso > 0 && !tramite[pasosTramite[indexPaso - 1]]) {
            return res.status(400).json({ message: "Debes completar los pasos anteriores antes de actualizar este" });
        }

        // Actualizar el paso
        tramite[pasoActualizar] = true;

        // Si se activa el último paso (entrega), finalizar el trámite
        if (pasoActualizar === "entrega") {
            tramite.fecha_fin = new Date();
            tramite.estado = "finalizado"; // Se cambia el estado a "finalizado"
        }

        // Guardar cambios
        await tramite.save();

        return res.status(200).json({ message: "Trámite actualizado exitosamente", tramite });
    } catch (error) {
        console.error(error);
        return res.status(500).json({ message: "Error al actualizar el trámite" });
    }
};

const CancelTramite = async (req, res = response) => {
    try {
        const { id } = req.params; // ID del trámite
        const { id_usuario } = req.body; // ID del usuario que intenta cancelar

        // Buscar el trámite
        const tramite = await Tramites.findByPk(id);
        if (!tramite) {
            return res.status(404).json({ message: "Trámite no encontrado" });
        }

        // Verificar si el trámite está activo
        if (tramite.estado !== "activo") {
            return res.status(400).json({ message: "El trámite ya ha sido cancelado o finalizado" });
        }
        
        // Verificar si el usuario es el comprador o el vendedor
        if (tramite.id_comprador !== id_usuario && tramite.id_vendedor !== id_usuario) {
            return res.status(403).json({ message: "No tienes permiso para cancelar este trámite" });
        }

        // Actualizar el estado del trámite a "cancelado"
        await Tramites.update(
            {
                entrega: false, // Si se cancela, aseguramos que la entrega esté en false
                fecha_fin: new Date(), // Fecha en la que se cancela el trámite
                estado: "cancelado" // Cambio de estado
            },
            {
                where: { id: id } // Filtra por el ID del trámite
            }
        );

        return res.status(200).json({ message: "Trámite cancelado exitosamente" });
    } catch (error) {
        console.error(error);
        return res.status(500).json({ message: "Error al cancelar el trámite" });
    }
};
module.exports = {
    tramiteGet,
    tramitesByIdGet,
    tramitesByCompradorGet,
    tramitesByVendedorGet,
    tramitePost,
    UpdatePasos,
    CancelTramite,
}
