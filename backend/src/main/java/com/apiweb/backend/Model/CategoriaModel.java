package com.apiweb.backend.Model;

import jakarta.persistence.Entity;
import jakarta.persistence.Id;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.ManyToOne;
import jakarta.persistence.Table;
import lombok.Data;
import lombok.AllArgsConstructor;
import lombok.NoArgsConstructor;

@Data
@Table(name = "categoria")
@Entity
@AllArgsConstructor
@NoArgsConstructor
public class CategoriaModel {

    @Id
    private Integer idCategoria;
    
    private String nombre;

    @ManyToOne
    @JoinColumn(name = "SubIdCategoria")
    private CategoriaModel SubIdCategoria;
}