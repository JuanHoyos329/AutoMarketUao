package AutoMarketUao.com.demo.Repository;

import java.time.Year;
import java.util.List;


import org.springframework.data.jpa.repository.JpaRepository;

import AutoMarketUao.com.demo.Model.PublicacionesModel;

public interface IPublicacionesRepository extends JpaRepository<PublicacionesModel, Integer> {

    List<PublicacionesModel> findByMarca(String marca);
    List<PublicacionesModel> findByModelo(String modelo);
    List<PublicacionesModel> findByAñoBetween(Year añoInicial, Year añofinal);
    List<PublicacionesModel> findByPrecioBetween(Integer precioMin, Integer precioMax);



    //Aqui van las consultas 
}
