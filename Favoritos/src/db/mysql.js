const mysql = require("mysql2/promise");

const connection = mysql.createPool({
    host: "192.168.100.3",
    user: "root",
    password: "root", 
    database: "favoritosdb", 
    port: "3309"
});

module.exports = connection;



