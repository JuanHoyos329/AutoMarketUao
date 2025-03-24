package com.automarket.Repository;

import java.time.Year;
import java.util.List;


import org.springframework.data.jpa.repository.JpaRepository;

import com.automarket.Model.PublicacionesModel;

public interface PublicacionesRepository extends JpaRepository<PublicacionesModel, Integer> {

    List<PublicacionesModel> findByMarca(String marca);
    List<PublicacionesModel> findByModelo(String modelo);
    List<PublicacionesModel> findByAñoBetween(Year añoInicial, Year añofinal);
    List<PublicacionesModel> findByPrecioBetween(Integer precioMin, Integer precioMax);



    //Aqui van las consultas 
}
