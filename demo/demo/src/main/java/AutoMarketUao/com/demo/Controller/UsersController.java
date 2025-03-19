package AutoMarketUao.com.demo.Controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import AutoMarketUao.com.demo.Model.UsersModel;
import AutoMarketUao.com.demo.Service.IUserService;

@RestController
@RequestMapping("/automarketuao/users")
public class UsersController {
    
    @Autowired
    private IUserService userService;

    @PostMapping("/create")
    public ResponseEntity<String> createUser(@RequestBody UsersModel user){
        try {
            return new ResponseEntity<>(userService.createUser(user), HttpStatus.CREATED);
        } catch (IllegalArgumentException e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST).body(e.getMessage());        }
        
    }
}
