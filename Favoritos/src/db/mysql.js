const mysql = require("mysql2/promise");

const connection = mysql.createPool({
    host: "localhost",
    user: "root",
    password: "root", 
    database: "favoritosdb", 
    port: "3306"
});

module.exports = connection;