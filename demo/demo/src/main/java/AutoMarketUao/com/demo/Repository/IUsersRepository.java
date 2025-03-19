package AutoMarketUao.com.demo.Repository;

import org.springframework.data.jpa.repository.JpaRepository;

import AutoMarketUao.com.demo.Model.UsersModel;

public interface IUsersRepository extends JpaRepository<UsersModel, Integer> {

    boolean existsByUsername(String username);
    
}