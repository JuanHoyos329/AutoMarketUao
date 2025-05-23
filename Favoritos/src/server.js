const express = require("express");
const cors = require("cors");
const app = express();
const port = 3002;

// Middleware
app.use(express.json());
app.use(cors());

// Rutas
const favoritosRoutes = require("./routes/favoritos");
app.use("/favoritos", favoritosRoutes);

// Iniciar el servidor
app.listen(port, "0.0.0.0", () => {
    console.log(`Servidor corriendo en el puerto ${port}`);
});

process.on("uncaughtException", (err) => {
    console.error("Excepción no capturada:", err);
});

process.on("unhandledRejection", (reason, promise) => {
    console.error("Promesa no manejada:", reason);
});

