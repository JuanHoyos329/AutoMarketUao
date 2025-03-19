package AutoMarketUao.com.demo.Service;

import AutoMarketUao.com.demo.Model.UsersModel;

public interface IUserService {
    String createUser(UsersModel user);
    String updateUser(Integer idUser, UsersModel user);
    String deleteUser(Integer idUser, UsersModel user);
    UsersModel foundUser(UsersModel user);
}
