const { Sequelize } = require('sequelize');

const bdmysql = new Sequelize(
    'backtramites', //La base de datos
    'root', //Usuario
    'root', //Contraseña
    {
        host: 'mysql', //Nombre Host
        dialect: 'mysql' // MYSQL
    }
);


module.exports = {
    bdmysql,
}
