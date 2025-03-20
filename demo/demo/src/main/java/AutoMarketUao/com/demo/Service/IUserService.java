package AutoMarketUao.com.demo.Service;

import AutoMarketUao.com.demo.Model.UsersModel;

public interface IUserService {
    String createUser(UsersModel user);
    String updateUser(UsersModel user);
    String deleteUser(UsersModel user);
    UsersModel foundUser(UsersModel user);
    UsersModel foundUserByUsername(String username);
}
