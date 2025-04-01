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
//Insertar personas
const tramitePost = async (req, res = response) => {
    try {
        const { id_publicacion, id_comprador } = req.body;
        
        // 1. Obtener id_vendedor desde el microservicio de publicaciones
        const publicacionResponse = await axios.get(`http://localhost:8080/automarket/publicaciones/${id_publicacion}`);
        const id_vendedor = publicacionResponse.data.userId;
        
        // 2. Obtener datos del vendedor desde el microservicio de usuarios
        const vendedorResponse = await axios.get(`http://localhost:8081/automarketuao/users/read/id/${id_vendedor}`);
        const { name, last_name, phone, email } = vendedorResponse.data;

        // 3. Obtener datos del comprador desde el microservicio de usuarios
        const compradorResponse = await axios.get(`http://localhost:8081/automarketuao/users/read/id/${id_comprador}`);
        const { name: nameComprador, last_name: lastNameComprador, phone: phoneComprador, email: emailComprador } = compradorResponse.data;
        
        // 4. Crear el objeto del trámite con la información obtenida
        const nuevoTramite = await Tramites.create({
            id_vendedor,
            user_vendedor: `${name} ${last_name}`,
            tel_vendedor: phone,
            email_vendedor: email,
            id_comprador,
            user_comprador: `${nameComprador} ${lastNameComprador}`,
            tel_comprador: phoneComprador,
            email_comprador: emailComprador,
            fecha_inicio: new Date(), // Fecha actual
            revision_doc: false,
            cita: false,
            contrato: false,
            pago: false,
            Traspaso: false,
            entrega: false,
            fecha_fin: null, // Se llenará cuando termine el proceso
            estado: "activo"
        });

        // Devolver la respuesta con el id generado
        res.status(201).json({ mensaje: 'Trámite creado exitosamente', tramite: nuevoTramite });
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
    tramitePost,
    UpdatePasos,
    CancelTramite,
}