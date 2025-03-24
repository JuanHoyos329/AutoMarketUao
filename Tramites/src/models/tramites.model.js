//Aquí se crean las tablas y sus atributos

const { DataTypes } = require('sequelize');
const { bdmysql } = require('../src/database/MariaDbConnection');

const Tramites = bdmysql.define('Tramites',
    {
        // Model attributes are defined here
        'id': {
            type: DataTypes.INTEGER,
            //allowNull: false,
            primaryKey: true,
            autoIncrement: true
        },

        'id_vendedor': {
            type: DataTypes.INTEGER,
            allowNull: false
            // allowNull defaults to true
        },
        'user_vendedor': {
            type: DataTypes.STRING,
            allowNull: false
            // allowNull defaults to true
        },
        'id_comprador': {
            type: DataTypes.INTEGER,
            allowNull: false
            // allowNull defaults to true
        },
        'user_comprador': {
            type: DataTypes.STRING,
            allowNull: false
            // allowNull defaults to true
        },
        'tel_vendedor': {
            type: DataTypes.STRING,
            allowNull: false
            // allowNull defaults to true
        },
        'tel_comprador': {
            type: DataTypes.STRING,
            allowNull: false
            // allowNull defaults to true    
        },
        'email_vendedor': {
            type: DataTypes.STRING,
            allowNull: false
            // allowNull defaults to true
        },
        'email_comprador': {
            type: DataTypes.STRING,
            allowNull: false
            // allowNull defaults to true
        },
        'fecha_inicio': {
            type: DataTypes.DATE,
            allowNull: false
        },
        'revision_doc': {
            type: DataTypes.BOOLEAN,
            allowNull: false
        },
        'cita': {
            type: DataTypes.BOOLEAN,
            allowNull: false
        },
        'contrato': {
            type: DataTypes.BOOLEAN,
            allowNull: false
        }, 
        'pago': {
            type: DataTypes.BOOLEAN,
            allowNull: false
        },
        'Traspaso': {
            type: DataTypes.BOOLEAN,
            allowNull: false
        },
        'entrega': {
            type: DataTypes.BOOLEAN,
            allowNull: false
        },
        'fecha_fin': {
            type: DataTypes.DATE,
            allowNull: true
        },
        'estado': {
            type: DataTypes.STRING,
            allowNull: false
        }
    },


    {
        //Maintain table name don't plurilize
        freezeTableName: true,

        // I don't want createdAt
        createdAt: false,

        // I don't want updatedAt
        updatedAt: false
    }
);



module.exports = {
    Tramites,
}
