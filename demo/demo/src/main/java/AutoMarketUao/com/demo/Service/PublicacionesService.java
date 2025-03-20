package AutoMarketUao.com.demo.Service;

import java.time.Year;
import java.util.List;
import java.util.Optional;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import AutoMarketUao.Exception.RecursoNoEncontradoException;
import AutoMarketUao.com.demo.Model.PublicacionesModel;
import AutoMarketUao.com.demo.Repository.IPublicacionesRepository;

@Service
public class PublicacionesService implements IPublicacionesService{
    @Autowired
    IPublicacionesRepository publicacionesRepository;

    @Override
    public String createPublicacion(PublicacionesModel publicacion) {
        publicacionesRepository.save(publicacion);
        return "La publicación "+ publicacion.getIdPublicacion() +", fue publicada con exito.";

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
        publicacionRecuperada.setAño(publicacion.getAño());
        publicacionRecuperada.setDescripcion(publicacion.getDescripcion());
        publicacionRecuperada.setKilometraje(publicacion.getKilometraje());
        publicacionRecuperada.setMarca(publicacion.getMarca());
        publicacionRecuperada.setPrecio(publicacion.getPrecio());
        publicacionesRepository.save(publicacionRecuperada);
        return "la publicación con el Id" + idPublicacion + " ha sido actualizado correctamente.";
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
        return publicacionesRepository.findByModelo(modelo);
    }

    @Override
    public List<PublicacionesModel> buscarPorAño(Year añoInicial, Year añoFinal) {
        return publicacionesRepository.findByAñoBetween(añoInicial, añoFinal);
    }

    @Override
    public List<PublicacionesModel> buscarPorRangoDePrecio(Integer precioMin, Integer precioMax) {
        return publicacionesRepository.findByPrecioBetween(precioMin, precioMax);
    } 
        
}



    

