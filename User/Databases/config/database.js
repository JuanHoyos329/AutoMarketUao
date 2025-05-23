const { Sequelize } = require("sequelize");

const sequelize = new Sequelize("usersautomarketuao", "root", "root", {
  host: "localhost", // Dirección del servidor de la base de datos
  port: 3306,
  dialect: "mysql", // Tipo de base de datos
});

module.exports = sequelize;
