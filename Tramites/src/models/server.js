const express = require('express')
const cors = require('cors') //Cors domain al tener la api en otra parte

const { bdmysql } = require('../src/database/MariaDbConnection'); //Aqui es donde instancio la BD

class Server {

    constructor() {
 
        this.app = express();
        this.port = process.env.PORT;

        
        this.pathsMySql = {
            tramites: '/api/tramites', //Rutas para acceder a la info
        }
      


        this.app.get('/', function (req, res) {
            res.send('Hola Mundo a todos... DESDE UNA CLASE')
        })
        

        //Aqui me conecto a la BD
        this.dbConnection();

        //Middlewares
        this.middlewares();


        //Routes
        this.routes();

    }

    async dbConnection() {
        try {
            await bdmysql.authenticate();
            console.log('Connection OK a MySQL OK.');
        } catch (error) {
            console.error('No se pudo Conectar a la BD MySQL', error);
        }
    }

    routes() {
        this.app.use(this.pathsMySql.tramites, require('../src/routes/tramites'));
    }

    middlewares() {
        //CORS
        //Evitar errores por Cors Domain Access
        //Usado para evitar errores.
        this.app.use(cors());

        //Lectura y Parseo del body
        //JSON
        /*
        JSON (JavaScript Object Notation) 
        es un formato ligero de intercambio de datos. 
        JSON es de fácil lectura y escritura para los usuarios. 
        JSON es fácil de analizar y generar por parte de las máquinas. 
        JSON se basa en un subconjunto del lenguaje de programación JavaScript, 
        Estándar ECMA-262 3a Edición - Diciembre de 1999.
        */
        this.app.use(express.json());

        //Directorio publico
        this.app.use(express.static('public'));
    }


    listen() {
        this.app.listen(this.port, () => {
            console.log('Servidor corriendo en puerto', this.port);
        });
    }

}

module.exports = Server;