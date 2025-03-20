package AutoMarketUao.com.demo.Repository;

import java.util.Optional;

import org.springframework.data.jpa.repository.JpaRepository;

import AutoMarketUao.com.demo.Model.UsersModel;

public interface IUsersRepository extends JpaRepository<UsersModel, Integer> {

    boolean existsByUsername(String username);
    Optional<UsersModel> findByUsername(String username);
}