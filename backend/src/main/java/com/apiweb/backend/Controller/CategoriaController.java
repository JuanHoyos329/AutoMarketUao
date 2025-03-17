package com.apiweb.backend.Controller;

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

import com.apiweb.backend.Exeption.RecursoNoEncontradoException;
import com.apiweb.backend.Model.CategoriaModel;
import com.apiweb.backend.Service.ICategoriaService;

@RestController
@RequestMapping("/apiweb/v1/categorias")
public class CategoriaController {

    @Autowired
    ICategoriaService categoriaService;

    @PostMapping("/insertar")
    public ResponseEntity<String> crearCategoria(@RequestBody CategoriaModel categoria) {
        try {
            return new ResponseEntity<>(categoriaService.guardarCategoria(categoria), HttpStatus.CREATED);
        } catch (IllegalArgumentException e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST).body(e.getMessage());
        }
    }
    @GetMapping("/{id}")
    public ResponseEntity<?> buscarCategoriaPorId(@PathVariable int id){
        try {
            CategoriaModel categoria = categoriaService.buscarCategoriaPorId(id);
            return ResponseEntity.ok(categoria);
        } catch (RecursoNoEncontradoException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
        }
    }    
    @PutMapping("/editar/{id}")
    public ResponseEntity<String> editarCategoria(@PathVariable int id, @RequestBody CategoriaModel categoria) {
        try {
            return new ResponseEntity<>(categoriaService.editarCategoria(id, categoria), HttpStatus.OK);
        } catch (RecursoNoEncontradoException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(e.getMessage());
        }
    }
    @DeleteMapping("/eliminar/{id}")
    public ResponseEntity<String> eliminarCategoria(@PathVariable int id) {
        try {
            return new ResponseEntity<>(categoriaService.eliminarCategoria(id), HttpStatus.OK);
        } catch (RecursoNoEncontradoException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body("No se pudo eliminar la categoría: " + e.getMessage());
        }
    }
}