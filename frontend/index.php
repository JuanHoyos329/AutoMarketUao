<?php  
session_start();  

// Si el usuario vuelve a index.php, destruir la sesión correctamente  
if (isset($_SESSION["user"])) {  
    session_unset(); // Elimina todas las variables de sesión  
    session_destroy(); // Destruye la sesión  
}  
?>  

<!DOCTYPE html>  
<html lang="es">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Bienvenido | AutoMarketUAO</title>  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">  
</head>  
<body>  
    <div class="container mt-5">  
        <div class="card shadow-lg p-4 text-center">  
            <h2 class="mb-4">Bienvenido a AutoMarketUAO</h2>  
            <p>Elige una opción para continuar:</p>  
            <div class="d-flex justify-content-center gap-3">  
                <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>  
                <a href="crearUsuario.php" class="btn btn-success">Crear Usuario</a>  
            </div>  
        </div>  
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  
</body>  
</html>  