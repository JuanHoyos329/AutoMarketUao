package com.automarket.Controllers;
 
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

import com.automarket.Exeptions.DatosInvalidosException;
import com.automarket.Exeptions.IntegridadDeDatosException;
import com.automarket.Exeptions.ParametroInvalidoException;
import org.springframework.dao.DataIntegrityViolationException;
import com.automarket.Exeptions.RecursoNoEncontradoException;

import com.automarket.Model.PublicacionesModel;
import com.automarket.Service.IPublicacionesService;

@RestController
@RequestMapping ("/automarket/publicaciones")
public class PublicacionController {
    @Autowired
    IPublicacionesService publicacionesService;
    @PostMapping("/publicar")
    public ResponseEntity<String> crearPublicacion(@RequestBody PublicacionesModel publicacion) {
        // Validaciones antes de guardar
        if (publicacion.getPrecio() == null || publicacion.getPrecio() <= 0) {
            throw new DatosInvalidosException("El precio debe ser mayor a 0.");
        }
    
        if (publicacion.getAno() == null || publicacion.getAno() < 1900) {
            throw new DatosInvalidosException("El año debe ser mayor a 1900.");
        }
        if (publicacion.getKilometraje() == null || publicacion.getKilometraje() < 0) {
            throw new DatosInvalidosException("El kilometraje no puede ser negativo.");
        }
    
        try {
            return new ResponseEntity<>(publicacionesService.createPublicacion(publicacion), HttpStatus.CREATED);
        } catch (DataIntegrityViolationException e) {
            throw new IntegridadDeDatosException("Error: La publicación no pudo guardarse debido a una violación de integridad.");
        } catch (Exception e) {
            throw new RuntimeException("Error desconocido al crear la publicación: " + e.getMessage());
        }
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
    @PutMapping("/editar/{idPublicacion}")
public ResponseEntity<String> editarPublicacion(@PathVariable int idPublicacion, @RequestBody PublicacionesModel publicacion) {
    if (publicacion.getPrecio() == null || publicacion.getPrecio() <= 0) {
        throw new DatosInvalidosException("El precio debe ser mayor a 0.");
    }

    if (publicacion.getAno() == null || publicacion.getAno() < 1900) {
        throw new DatosInvalidosException("El año debe ser mayor a 1900.");
    }
    if (publicacion.getKilometraje() == null || publicacion.getKilometraje() < 0) {
        throw new DatosInvalidosException("El kilometraje no puede ser negativo.");
    }

    try {
        return new ResponseEntity<>(publicacionesService.createPublicacion(publicacion), HttpStatus.CREATED);
    } catch (DataIntegrityViolationException e) {
        throw new IntegridadDeDatosException("Error: La publicación no pudo guardarse debido a una violación de integridad.");
    } catch (Exception e) {
        throw new RuntimeException("Error desconocido al crear la publicación: " + e.getMessage());
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
            List<PublicacionesModel> publicaciones = publicacionesService.buscarPorModelo(modelo);
            return ResponseEntity.ok(publicaciones);
        } catch (RecursoNoEncontradoException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
        }
    
    }

    @GetMapping("/buscar/año")
    public ResponseEntity<?> buscarPorAño(@RequestParam("anoI") Integer anoI, @RequestParam("anoF") Integer anoF) { 
        if (anoI < 1900 || anoF < 1900 || anoI > anoF) {
            throw new ParametroInvalidoException("El rango de años es inválido. Debe ser entre 1900 y el año actual.");
        }
        return ResponseEntity.ok(publicacionesService.buscarPorAno(anoI, anoF));
    }

// Buscar por rango de precio
@GetMapping("/buscar/precio")
public ResponseEntity<?> buscarPorRangoDePrecio(@RequestParam("min") Integer min, @RequestParam("max") Integer max) {
    try {
        List<PublicacionesModel> publicaciones = publicacionesService.buscarPorRangoDePrecio(min, max);
        
        if (publicaciones.isEmpty()) {
            throw new RecursoNoEncontradoException("No se encontraron publicaciones en el rango de precio: " + min + " - " + max);
        }

        return ResponseEntity.ok(publicaciones);
    } catch (RecursoNoEncontradoException e) {
        return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
    } catch (Exception e) {
        return ResponseEntity.status(HttpStatus.BAD_REQUEST).body("Error al procesar la solicitud. Verifica los parámetros.");
    }
}

}


