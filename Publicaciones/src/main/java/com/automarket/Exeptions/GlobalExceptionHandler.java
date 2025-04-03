package com.automarket.Exeptions;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.bind.annotation.RestControllerAdvice;

@RestControllerAdvice
public class GlobalExceptionHandler {

    @ExceptionHandler(DatosInvalidosException.class)
    public ResponseEntity<String> handleDatosInvalidosException(DatosInvalidosException e) {
        return ResponseEntity.status(HttpStatus.BAD_REQUEST).body("❌ Datos inválidos: " + e.getMessage());
    }

    @ExceptionHandler(IntegridadDeDatosException.class)
    public ResponseEntity<String> handleIntegridadDeDatosException(IntegridadDeDatosException e) {
        return ResponseEntity.status(HttpStatus.CONFLICT).body("⚠️ Integridad de datos violada: " + e.getMessage());
    }

    @ExceptionHandler(RecursoNoEncontradoException.class)
    public ResponseEntity<String> handleRecursoNoEncontradoException(RecursoNoEncontradoException e) {
        return ResponseEntity.status(HttpStatus.NOT_FOUND).body("🔍 Recurso no encontrado: " + e.getMessage());
    }

    @ExceptionHandler(Exception.class)
    public ResponseEntity<String> handleGeneralException(Exception e) {
        return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body("🚨 Error interno: " + e.getMessage());
    }
}
