package AutoMarketUao.com.demo.Controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.PutMapping;
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
    public ResponseEntity<String> createUser(@RequestBody UsersModel user) {
        try {
            return new ResponseEntity<>(userService.createUser(user), HttpStatus.CREATED);
        } catch (IllegalArgumentException e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST).body(e.getMessage());
        }

    }

    @GetMapping("/read/{username}")
    public ResponseEntity<UsersModel> readUser(@PathVariable("username") String username) {
        try {
            UsersModel user = userService.foundUserByUsername(username);
            return ResponseEntity.ok(user);
        } catch (IllegalArgumentException e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST).build();
        }
    }

    @PutMapping("/update/{username}")
    public ResponseEntity<String> updateUser(@PathVariable("username") String username, @RequestBody UsersModel updateUser) {
        try {
            // Establece el username del objeto updateUser con el valor de la URL
            updateUser.setUsername(username);
            String response = userService.updateUser(updateUser);
            return ResponseEntity.ok(response);
        } catch (IllegalArgumentException e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST).body(e.getMessage());
        }
    }

    @DeleteMapping("/delete/{username}")
    public ResponseEntity<String> deleteUser(@PathVariable("username") String username) {
        try {
            // Busca el usuario por el username y luego elimínalo
            UsersModel user = userService.foundUserByUsername(username);
            userService.deleteUser(user);
            return ResponseEntity.ok("El usuario " + username + " fue eliminado correctamente.");
        } catch (IllegalArgumentException e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST).body(e.getMessage());
        }
    }

}
