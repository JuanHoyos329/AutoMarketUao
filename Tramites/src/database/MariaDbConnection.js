const { Sequelize } = require('sequelize');

const bdmysql = new Sequelize(
    'backtramites', //La base de datos
    'root', //Usuario
    'root', //Contraseña
    {
        host: 'db-tramites', //Nombre Host
        dialect: 'mysql' // MYSQL
    }
);

const bdmysql1 = new Sequelize(
    'test',
    'root',
    'root',
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
