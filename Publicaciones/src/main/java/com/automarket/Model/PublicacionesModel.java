package com.automarket.Model;

import java.time.Year;

import com.automarket.Model.ENUM.estado;
import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.EnumType;
import jakarta.persistence.Enumerated;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Entity
@Data
@Table(name = "publicaciones")
@AllArgsConstructor
@NoArgsConstructor


public class PublicacionesModel {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer idPublicacion;
    private Integer idusuario;
    private String marca;
    private String modelo;
    private Year año;
    private Integer precio;
    private Integer kilometraje;
    private String tipo_combustible;
    private String transmision;
    private Float tamaño_motor;
    private Integer puertas;
    private String ultimo_dueño;
    private String descripcion;
    private String ubicacion;
     @Column(name = "estado")
        @Enumerated (EnumType.STRING)
        private estado estado;

    
}
