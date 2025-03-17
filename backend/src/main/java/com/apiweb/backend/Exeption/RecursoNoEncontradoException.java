package com.apiweb.backend.Exeption;

public class RecursoNoEncontradoException extends RuntimeException {
    public RecursoNoEncontradoException (String mensaje) {
        super(mensaje);
    }
    
}
