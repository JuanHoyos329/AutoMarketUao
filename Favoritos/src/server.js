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
app.listen(port, () => {
    console.log(`Servidor corriendo en el puerto ${port}`);
});

