package AutoMarketUao.com.demo.Model;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@Table(name = "users")
@Entity
@AllArgsConstructor
@NoArgsConstructor

public class UsersModel {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer idUser;

    private String name;
    private String last_name;
    private String username;
    private String password;
    private String email;
    private String phone;
        
}
