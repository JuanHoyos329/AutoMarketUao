package com.apiweb.backend.Service;

import com.apiweb.backend.Model.CategoriaModel;


public interface ICategoriaService {

    String guardarCategoria(CategoriaModel categoria);
    CategoriaModel buscarCategoriaPorId(int categoriaId);
    String editarCategoria(int categoriaId, CategoriaModel categoria); 
    String eliminarCategoria(int categoriaId);
}
