package com.automarket.Exeptions;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.bind.annotation.RestControllerAdvice;

import java.util.HashMap;
import java.util.Map;

@RestControllerAdvice
public class GlobalExceptionHandler {

    private ResponseEntity<Map<String, String>> buildErrorResponse(String mensaje, HttpStatus status) {
        Map<String, String> errorResponse = new HashMap<>();
        errorResponse.put("message", mensaje);  // 🔥 Devolvemos un JSON con clave "message"
        return new ResponseEntity<>(errorResponse, status);
    }

    @ExceptionHandler(DatosInvalidosException.class)
    public ResponseEntity<Map<String, String>> handleDatosInvalidosException(DatosInvalidosException e) {
        return buildErrorResponse("❌ Datos inválidos: " + e.getMessage(), HttpStatus.BAD_REQUEST);
    }

    @ExceptionHandler(IntegridadDeDatosException.class)
    public ResponseEntity<Map<String, String>> handleIntegridadDeDatosException(IntegridadDeDatosException e) {
        return buildErrorResponse("⚠️ Integridad de datos violada: " + e.getMessage(), HttpStatus.CONFLICT);
    }

    @ExceptionHandler(RecursoNoEncontradoException.class)
    public ResponseEntity<Map<String, String>> handleRecursoNoEncontradoException(RecursoNoEncontradoException e) {
        return buildErrorResponse("🔍 Recurso no encontrado: " + e.getMessage(), HttpStatus.NOT_FOUND);
    }

    @ExceptionHandler(Exception.class)
    public ResponseEntity<Map<String, String>> handleGeneralException(Exception e) {
        return buildErrorResponse("🚨 Error interno: " + e.getMessage(), HttpStatus.INTERNAL_SERVER_ERROR);
    }
}
