package AutoMarketUao.com.demo.Service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import AutoMarketUao.com.demo.Model.UsersModel;
import AutoMarketUao.com.demo.Repository.IUsersRepository;

@Service
public class UsersService implements IUserService {

    @Autowired
    private IUsersRepository usersRepository;

    @Override
    public String createUser(UsersModel user) {
        usersRepository.save(user);
        return "El usuario " + user.getUsername() + " fue creado correctamente.";
    }

    @Override
    public String updateUser(UsersModel user) {
        UsersModel userUpdate = usersRepository.findByUsername(user.getUsername())
                .orElseThrow(() -> new IllegalArgumentException(
                        "Usuario no encontrado con username: " + user.getUsername()));

        if (!userUpdate.getUsername().equals(user.getUsername())) {
            if (usersRepository.findByUsername(user.getUsername()).isPresent()) {
                throw new IllegalArgumentException("El nuevo username ya está en uso.");
            }
            usersRepository.delete(userUpdate);
            userUpdate.setUsername(user.getUsername());
            usersRepository.save(userUpdate);
        }

        userUpdate.setName(user.getName());
        userUpdate.setLast_name(user.getLast_name());
        userUpdate.setPassword(user.getPassword());
        userUpdate.setEmail(user.getEmail());
        userUpdate.setPhone(user.getPhone());

        usersRepository.save(userUpdate);

        return "El usuario fue actualizado correctamente.";
    }

    @Override
    public String deleteUser(UsersModel user) {
        if (usersRepository.existsByUsername(user.getUsername())) {
            usersRepository.delete(user);
            return "El usuario " + user.getUsername() + " fue eliminado correctamente.";
        } else {
            return "El usuario con id " + user + " no existe.";
        }
    }

    public UsersModel foundUser(UsersModel user) {
        return usersRepository.findByUsername(user.getUsername())
                .orElseThrow(() -> new IllegalArgumentException(
                        "Usuario no encontrado con username: " + user.getUsername()));
    }

    @Override
    public UsersModel foundUserByUsername(String username) {
        return usersRepository.findByUsername(username)
                .orElseThrow(() -> new IllegalArgumentException("Usuario no encontrado con username: " + username));
    }

}
