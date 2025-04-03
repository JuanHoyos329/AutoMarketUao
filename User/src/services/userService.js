const User = require("../models/User");
const { sequelize } = require("../../Databases/config/database");

class UserService {
  async createUser(userData) {
    // Verificar si el email ya está en uso
    const existingEmail = await User.findOne({ where: { email: userData.email } });
    if (existingEmail) {
      throw new Error(`El correo ${userData.email} ya está en uso por otro usuario.`);
    }

    // Verificar si el username ya está en uso
    const existingUsername = await User.findOne({ where: { username: userData.username } });
    if (existingUsername) {
      throw new Error(`El username "${userData.username}" ya está en uso.`);
    }

    // Asignar el rol (por defecto "user")
    if (!userData.role || (userData.role !== "admin" && userData.role !== "user")) {
      userData.role = "user";
    }

    // Crear usuario sin encriptar la contraseña
    const user = await User.create(userData);
    return `El usuario ${user.username} fue creado correctamente.`;
  }

  async deleteUser(username) {
    const user = await User.findOne({ where: { username } });
    if (!user) throw new Error(`Usuario con username ${username} no existe.`);
    if (user.role == "admin") {
      throw new Error("No puedes eliminar un usuario administrador.");
    }

    await user.destroy();
    return `El usuario ${username} fue eliminado correctamente.`;
  }

  async getUserByEmail(email) {
    const user = await User.findOne({ where: { email } });
    if (!user) throw new Error(`Usuario no encontrado con email: ${email}`);
    return user;
  }

  //Para que Miguel pueda obtener el usuario por su id
  async getUserByUserId(userId) {
    const user = await User.findOne({ where: { userId } });
    if (!user) throw new Error(`Usuario no encontrado con ID: ${userId}`);
    return user;
  }

  async updateUserRole(email, newRole) {
    // Verificar si el nuevo rol es válido
    if (newRole !== "admin" && newRole !== "user") {
      throw new Error("Rol no válido.");
    }

    // Buscar al usuario por email
    const user = await User.findOne({ where: { email } });
    if (!user) {
      throw new Error(`Usuario no encontrado con email: ${email}`);
    }

    // Actualizar el rol del usuario
    user.role = newRole;

    // Guardar los cambios en la base de datos
    await user.save();

    return `El rol del usuario con email ${email} fue actualizado a ${newRole}.`;
  }

  async getAllUsers() {
    const users = await User.findAll({ attributes: { exclude: ["password"] } }); // Excluir contraseña por seguridad
    if (users.length === 0) {
      throw new Error("No hay ningún usuario en la base de datos.");
    }
    return users;
  }

  async updateUser(email, userData) {
    const user = await User.findOne({ where: { email } });
    if (!user) {
      throw new Error(`Usuario no encontrado con email: ${email}`);
    }

    // Verificar si el nuevo correo ya está en uso por otro usuario
    if (userData.email && userData.email !== user.email) {
      const existingEmail = await User.findOne({ where: { email: userData.email } });
      if (existingEmail) {
        throw new Error(`El correo ${userData.email} ya está en uso por otro usuario.`);
      }
    }

    // Verificar si el nuevo username ya está en uso por otro usuario
    if (userData.username && userData.username !== user.username) {
      const existingUsername = await User.findOne({ where: { username: userData.username } });
      if (existingUsername) {
        throw new Error(`El username "${userData.username}" ya está en uso.`);
      }
    }

    // Mantener la contraseña existente (si no se proporciona una nueva contraseña)
    if (!userData.password || userData.password === "") {
      userData.password = user.password; // Mantener la contraseña existente
    }

    // Actualizar la información del usuario
    await user.update(userData);

    return `El usuario con email ${email} fue actualizado correctamente.`;
  }
}

module.exports = new UserService();
