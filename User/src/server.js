const express = require("express");
const sequelize = require("../Databases/config/database");
const userRoutes = require("./controllers/userController");
const cors = require("cors");

const app = express();

app.use(
  cors({
    origin: ["localhost", "localhost:3000"],
    methods: ["GET", "POST", "PUT", "DELETE"],
    allowedHeaders: ["Content-Type", "Authorization"],
  })
);

app.use(express.json());

app.use("/automarketuao/users", userRoutes);

const PORT = process.env.PORT || 8081;

sequelize
  .sync()
  .then(() => {
    console.log("Base de datos conectada");
    app.listen(PORT, () => {
      console.log(`Servidor corriendo en http://localhost:8081`);
    });
  })
  .catch((err) => console.error("Error al conectar la base de datos:", err));
