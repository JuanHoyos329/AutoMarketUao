const { DataTypes } = require("sequelize");
const sequelize = require("../../Databases/config/database");

const Users = sequelize.define(
  "Users",
  {
    userId:{
      type: DataTypes.INTEGER,
      allowNull: false,
      unique: true,
      autoIncrement: true
    },
    email: {
      type: DataTypes.STRING,
      allowNull: false,
      primaryKey: true, // Definir email como PRIMARY KEY
      validate: {
        isEmail: true,
      },
    },
    name: {
      type: DataTypes.STRING,
      allowNull: false,
    },
    last_name: {
      type: DataTypes.STRING,
      allowNull: false,
    },
    username: {
      type: DataTypes.STRING,
      allowNull: false,
      unique: true,
    },
    password: {
      type: DataTypes.STRING,
      allowNull: false,
    },
    phone: {
      type: DataTypes.STRING,
      allowNull: false,
    },
    role: {  
      type: DataTypes.ENUM("user", "admin"),
      allowNull: false,
      defaultValue: "user",
    },
  },
  {
    timestamps: false, // Evita que Sequelize agregue createdAt y updatedAt
    freezeTableName: true, // Evita que Sequelize pluralice el nombre de la tabla
  }
);

module.exports = Users;
