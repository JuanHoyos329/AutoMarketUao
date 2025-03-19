package AutoMarketUao.com.demo.Service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import AutoMarketUao.com.demo.Model.UsersModel;
import AutoMarketUao.com.demo.Repository.IUsersRepository;


@Service
public class UsersService implements IUserService{

    @Autowired
    private IUsersRepository usersRepository;

    @Override
    public String createUser(UsersModel user) {
        usersRepository.save(user);
        return "El usuario " + user.getUsername() + " fue creado correctamente.";
    }

    @Override
    public String updateUser(Integer idUser, UsersModel user) {
        UsersModel userUpdate = foundUser(user);

        if (userUpdate == null) {
            return "El usuario con id " + idUser + " no existe.";
        }
            else {
                userUpdate.setName(user.getName());
                userUpdate.setLast_name(user.getLast_name());
                userUpdate.setUsername(user.getUsername());
                userUpdate.setPassword(user.getPassword());
                userUpdate.setEmail(user.getEmail());
                userUpdate.setPhone(user.getPhone());
                usersRepository.save(userUpdate);

                return "El usuario " + user.getUsername() + " fue actualizado correctamente.";
            }
        
    }

    @Override
    public String deleteUser(Integer idUser, UsersModel user) {
        if(usersRepository.existsByUsername(user.getUsername())) {
            usersRepository.delete(user);
            return "El usuario " + user.getUsername() + " fue eliminado correctamente.";
        }
        else {
            return "El usuario con id " + idUser + " no existe.";
        }
    }

    @Override
    public UsersModel foundUser(UsersModel user) {
        // TODO Auto-generated method stub
        throw new UnsupportedOperationException("Unimplemented method 'foundUser'");
    }
    
}
