package com.automarket.Service;

import java.time.Year;
import java.util.List;

import com.automarket.Model.PublicacionesModel;

public interface IPublicacionesService {
    String createPublicacion(PublicacionesModel publicacion);
    PublicacionesModel buscarPublicacionPorId(Integer idPublicacion);
    List<PublicacionesModel>listarTodasLasPublicaciones();
    String deletePublicacion(Integer idPublicacion );
    String updatePublicacion(Integer idPublicacion, PublicacionesModel publicacion);
   

    List<PublicacionesModel> buscarPorMarca(String marca);
    List<PublicacionesModel> buscarPorModelo(String modelo);
    List<PublicacionesModel> buscarPorAño(Year añoInicial, Year añoFinal);
    List<PublicacionesModel> buscarPorRangoDePrecio(Integer precioMin, Integer precioMax);
}
