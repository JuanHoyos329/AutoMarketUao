const { Sequelize } = require("sequelize");

const sequelize = new Sequelize("usersautomarketuao", "root", "root", {
  host: "192.168.100.2", // Dirección del servidor de la base de datos
  port: 3306,
  dialect: "mysql", // Tipo de base de datos
});

module.exports = sequelize;
