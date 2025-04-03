package com.automarket.Model;

import com.automarket.Model.ENUM.estado;
import jakarta.persistence.*;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import java.util.Date;

@Entity
@Data
@Table(name = "publicaciones")
@AllArgsConstructor
@NoArgsConstructor
public class PublicacionesModel {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer idPublicacion;

    private Integer userId;
    private String marca;
    private String modelo;
    private Integer ano;
    private Integer precio;
    private Integer kilometraje;
    private String tipo_combustible;
    private String transmision;
    private Float tamano_motor;
    private Integer puertas;
    private String ultimo_dueno;
    private String descripcion;
    private String ubicacion;

    @Enumerated(EnumType.STRING)
    private estado estado;

    @Column(name = "fecha_publicacion", updatable = false, nullable = false)
    @Temporal(TemporalType.TIMESTAMP)
    private Date fechaPublicacion = new Date(); // Se asigna automáticamente la fecha actual
}
