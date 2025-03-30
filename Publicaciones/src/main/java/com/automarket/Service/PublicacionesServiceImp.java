package com.automarket.Service;
import java.util.List;
import java.util.Optional;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;


import com.automarket.Exeptions.RecursoNoEncontradoException;
import com.automarket.Model.PublicacionesModel;
import com.automarket.Repository.PublicacionesRepository;



@Service
public class PublicacionesServiceImp implements IPublicacionesService{
    @Autowired
    PublicacionesRepository publicacionesRepository;

    @Override
    public String createPublicacion(PublicacionesModel publicacion) {
        publicacionesRepository.save(publicacion);
        return "La publicación con id    "+ publicacion.getIdPublicacion()+", fue publicada con exito.";
    }
    @Override
    public PublicacionesModel buscarPublicacionPorId(Integer  idPublicacion) {
        Optional<PublicacionesModel> publicacionRecuperada = publicacionesRepository.findById(idPublicacion);
        return publicacionRecuperada.orElseThrow(()-> new RecursoNoEncontradoException
        ("Error! La publicación con el Id "+idPublicacion+", no existe en la BD o el id es incorrecto"));
    }

    @Override
    public List<PublicacionesModel> listarTodasLasPublicaciones() {
        return publicacionesRepository.findAll();
    }

    @Override
    public String deletePublicacion(Integer idPublicacion) {
    PublicacionesModel publicacionRecuperada = buscarPublicacionPorId(idPublicacion);
        publicacionesRepository.delete(publicacionRecuperada);
        return "La publicación con Id " + idPublicacion + " ha sido eliminada correctamente.";
        
    }

    @Override
    public String updatePublicacion(Integer idPublicacion, PublicacionesModel publicacion) {
        PublicacionesModel publicacionRecuperada = buscarPublicacionPorId(idPublicacion);
        publicacionRecuperada.setModelo(publicacion.getModelo());
       publicacionRecuperada.setAno(publicacion.getAno());
        publicacionRecuperada.setDescripcion(publicacion.getDescripcion());
        publicacionRecuperada.setKilometraje(publicacion.getKilometraje());
        publicacionRecuperada.setMarca(publicacion.getMarca());
        publicacionRecuperada.setPrecio(publicacion.getPrecio());
        publicacionRecuperada.setEstado(publicacion.getEstado());
        publicacionRecuperada.setTipo_combustible(publicacion.getTipo_combustible());
        publicacionRecuperada.setTransmision(publicacion.getTransmision());
        publicacionRecuperada.setTamano_motor(publicacion.getTamano_motor());
        publicacionRecuperada.setPuertas(publicacion.getPuertas());
        publicacionRecuperada.setUltimo_dueno(publicacion.getUltimo_dueno());
        publicacionRecuperada.setDescripcion(publicacion.getDescripcion());
        publicacionRecuperada.setUbicacion(publicacion.getUbicacion());
        publicacionRecuperada.setEstado(publicacion.getEstado());

        publicacionesRepository.save(publicacionRecuperada);
        return "la publicación con el Id " + idPublicacion + " ha sido actualizado correctamente.";
    }
    
    @Override
    
    public List<PublicacionesModel> buscarPorMarca(String marca) {
    List<PublicacionesModel> publicaciones = publicacionesRepository.findByMarca(marca);
    if (publicaciones.isEmpty()) {
        throw new RecursoNoEncontradoException("No se encontraron publicaciones para la marca: " + marca);}
        return publicaciones;
    }

    @Override
public List<PublicacionesModel> buscarPorModelo(String modelo) {
    List<PublicacionesModel> publicaciones = publicacionesRepository.findByModelo(modelo);
    if (publicaciones.isEmpty()) {
        throw new RecursoNoEncontradoException("No se encontraron publicaciones para el modelo: " + modelo);
    }
    return publicaciones;
}

@Override
public List<PublicacionesModel> buscarPorAno(Integer anoInicial, Integer anoFinal) {
    List<PublicacionesModel> publicaciones = publicacionesRepository.findByAnoBetween(anoInicial, anoFinal);
    if (publicaciones.isEmpty()) {
        throw new RecursoNoEncontradoException("No se encontraron publicaciones en el rango de años: " + anoInicial + " - " + anoFinal);
    }
    return publicaciones;
}

@Override
public List<PublicacionesModel> buscarPorRangoDePrecio(Integer precioMin, Integer precioMax) {
    List<PublicacionesModel> publicaciones = publicacionesRepository.findByPrecioBetween(precioMin, precioMax);
    if (publicaciones.isEmpty()) {
        throw new RecursoNoEncontradoException("No se encontraron publicaciones en el rango de precios: " + precioMin + " - " + precioMax);
    }
    return publicaciones;
} 


}
        
