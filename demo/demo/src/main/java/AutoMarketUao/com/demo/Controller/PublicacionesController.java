package AutoMarketUao.com.demo.Controller;

import java.time.Year;
import java.util.List;

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
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import AutoMarketUao.Exception.RecursoNoEncontradoException;
import AutoMarketUao.com.demo.Model.PublicacionesModel;
import AutoMarketUao.com.demo.Service.IPublicacionesService;

@RestController
@RequestMapping ("/automarketuao/publicaciones")
public class PublicacionesController {
    @Autowired
    IPublicacionesService publicacionesService;
    @PostMapping("/publicar")
    public ResponseEntity<String> crearPublicacion(@RequestBody PublicacionesModel publicacion){
        return new ResponseEntity<String>(publicacionesService.createPublicacion(publicacion),HttpStatus.OK);
    }
     @GetMapping("/{idPublicacion}")
    public ResponseEntity<?> buscarPublicacionPorId(@PathVariable int idPublicacion){
        try {
            PublicacionesModel publicacion = publicacionesService.buscarPublicacionPorId(idPublicacion);
            return ResponseEntity.ok(publicacion);
        } catch (RecursoNoEncontradoException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
        }
    }
    @GetMapping("/listarPublicaciones")
    public ResponseEntity<List<PublicacionesModel>> mostrarPublicaciones(){
        return new ResponseEntity<List<PublicacionesModel>>(publicacionesService.listarTodasLasPublicaciones(),HttpStatus.OK);
    }
    @DeleteMapping("/eliminar/{idPublicacion}")
    public ResponseEntity<String> eliminarPublicacion(@PathVariable int idPublicacion) {
        try {
            return new ResponseEntity<>(publicacionesService.deletePublicacion(idPublicacion), HttpStatus.OK);
        } catch (RecursoNoEncontradoException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
        }
    }
    @PutMapping("/editar/{id}")
    public ResponseEntity<String> editarPublicacion(@PathVariable int idPublicacion, @RequestBody PublicacionesModel publicacion) {
        try {
            return new ResponseEntity<>(publicacionesService.updatePublicacion(idPublicacion, publicacion), HttpStatus.OK);
        } catch (RecursoNoEncontradoException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
        }
    }
    @GetMapping("/buscar/marca/{marca}")
    public ResponseEntity<?> buscarPorMarca(@PathVariable String marca) {
        try {
            List<PublicacionesModel> publicaciones = publicacionesService.buscarPorMarca(marca);
            return ResponseEntity.ok(publicaciones);
        } catch (RecursoNoEncontradoException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
        }
    
    }


    @GetMapping("/buscar/modelo/{modelo}")
    public ResponseEntity<?> buscarPorModelo(@PathVariable String modelo) {
        try {
            List<PublicacionesModel> publicaciones = publicacionesService.buscarPorMarca(modelo);
            return ResponseEntity.ok(publicaciones);
        } catch (RecursoNoEncontradoException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
        }
    
    }

    @GetMapping("/buscar/año")
public ResponseEntity<?> buscarPorAño(@RequestParam Year añoI, @RequestParam Year añoF) {
    try {
        List<PublicacionesModel> publicaciones = publicacionesService.buscarPorAño(añoI, añoF);
        return ResponseEntity.ok(publicaciones);
    } catch (RecursoNoEncontradoException e) {
        return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
    }
}

// Buscar por rango de precio
@GetMapping("/buscar/precio")
public ResponseEntity<?> buscarPorRangoDePrecio(@RequestParam Integer min, @RequestParam Integer max) {
    try {
        List<PublicacionesModel> publicaciones = publicacionesService.buscarPorRangoDePrecio(min, max);
        return ResponseEntity.ok(publicaciones);
    } catch (RecursoNoEncontradoException e) {
        return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
    }}

}
