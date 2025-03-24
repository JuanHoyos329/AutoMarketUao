const { Sequelize } = require('sequelize');

const bdmysql = new Sequelize(
    'backTramites', //La base de datos
    'root', //Usuario
    '', //Contraseña
    {
        host: 'localhost', //Nombre Host
        dialect: 'mysql' // MYSQL
    }
);

const bdmysql1 = new Sequelize(
    'test',
    'root',
    '',
    {
        host: 'localhost',
        port: '8082',
        dialect: 'mariadb' //MariaDB
    }
);



module.exports = {
    bdmysql,
    bdmysql1
}