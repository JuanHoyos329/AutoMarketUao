const { Sequelize } = require("sequelize");

const sequelize = new Sequelize("usersautomarketuao", "root", "annie", {
  host: "localhost", // Dirección del servidor de la base de datos
  port: 3306,
  dialect: "mysql", // Tipo de base de datos
  dialectOptions: {
    ssl: false, // Equivalente a `useSSL=false` en tu configuración de Java
  },
  timezone: "Etc/UTC", // Equivalente a `serverTimezone=UTC`
});

module.exports = sequelize;
