const mysql = require("mysql2/promise");

const connection = mysql.createPool({
    host: "favoritos-mysql",
    user: "root",
    password: "root", 
    database: "favoritosdb", 
    port: "3309"
});

module.exports = connection;
