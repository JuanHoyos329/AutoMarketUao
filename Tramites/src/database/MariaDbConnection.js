const { Sequelize } = require('sequelize');

const bdmysql = new Sequelize(
    'backtramites', //La base de datos
    'root', //Usuario
    'annie', //Contraseña
    {
        host: 'localhost', //Nombre Host
        dialect: 'mysql' // MYSQL
    }
);

const bdmysql1 = new Sequelize(
    'test',
    'root',
    'annie',
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