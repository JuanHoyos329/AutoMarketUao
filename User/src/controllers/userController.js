const express = require("express");
const router = express.Router();
const userService = require("../services/userService");

// Crear un usuario con rol opcional
router.post("/create", async (req, res) => {
  try {
    const message = await userService.createUser(req.body);
    res.status(201).json({ message });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
});

// Obtener un usuario por email
router.get("/read/:email", async (req, res) => {
  try {
    const user = await userService.getUserByEmail(req.params.email);
    res.json(user);
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
});

// Actualizar un usuario
router.put("/update/:email", async (req, res) => {
  try {
    // Llamamos al servicio para actualizar el usuario pasando el email y los datos del cuerpo de la solicitud
    const message = await userService.updateUser(req.params.email, req.body);
    res.json({ message }); // Respondemos con el mensaje de éxito
  } catch (error) {
    // Si ocurre un error, respondemos con un mensaje de error y el código de estado 400
    res.status(400).json({ error: error.message });
  }
});

// Obtener todos los usuarios (solo para administradores en el frontend)
router.get("/all", async (req, res) => {
  try {
    const users = await userService.getAllUsers();
    res.json(users);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

router.delete("/delete/:username", async (req, res) => {
  try {
    const message = await userService.deleteUser(req.params.username);
    res.json({ message });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
});

// Ruta para actualizar el rol de un usuario
router.put("/updateRole", async (req, res) => {
  const { email, role } = req.body;

  try {
    // Llamar al servicio para actualizar el rol
    const message = await userService.updateUserRole(email, role);
    res.status(200).json({ message });
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
}
);
// Ruta para obtener un usuario por su ID
router.get("/read/id/:userId", async (req, res) => {
  try {
    const user = await userService.getUserByUserId(req.params.userId);
    res.json(user);
  } catch (error) {
    res.status(400).json({ error: error.message });
  }
});

module.exports = router;
