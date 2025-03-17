package com.apiweb.backend.Service;
import java.util.Optional;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.apiweb.backend.Exeption.RecursoNoEncontradoException;
import com.apiweb.backend.Model.CategoriaModel;
import com.apiweb.backend.Repository.ICategoriaRepository;

@Service
public class CategoriaServiceImp implements ICategoriaService{
    @Autowired
    ICategoriaRepository CategoriaRepository;
    @Override
    public String guardarCategoria(CategoriaModel Categoria) {
        if (CategoriaRepository.existsById(Categoria.getIdCategoria())) 
            throw new IllegalArgumentException("No puedes usar este idCategoria, ya está en uso.");
        CategoriaRepository.save(Categoria);
        return "La categoria " + Categoria.getNombre() + " fue creada correctamente";
    }
    @Override
    public CategoriaModel buscarCategoriaPorId(int categoriaId) {
        Optional<CategoriaModel> categoriaRecuperada = CategoriaRepository.findById(categoriaId);
        return categoriaRecuperada.orElseThrow(()-> new RecursoNoEncontradoException
        ("Error! El categoria con el Id "+categoriaId+", no existe en la BD o el id incorrecto"));
    }
    @Override
    public String editarCategoria(int categoriaId, CategoriaModel categoria) {
        CategoriaModel categoriaRecuperada = buscarCategoriaPorId(categoriaId);
        categoriaRecuperada.setNombre(categoria.getNombre());
        categoriaRecuperada.setSubIdCategoria(categoria.getSubIdCategoria());
        CategoriaRepository.save(categoriaRecuperada);
        return "La categoria con Id " + categoriaId + " ha sido actualizado correctamente.";
    }
    @Override
    public String eliminarCategoria(int categoriaId) {
        CategoriaModel categoriaExistente = buscarCategoriaPorId(categoriaId);
        CategoriaRepository.delete(categoriaExistente);
        return "La categoría con ID " + categoriaId + " ha sido eliminada correctamente.";
    }
}