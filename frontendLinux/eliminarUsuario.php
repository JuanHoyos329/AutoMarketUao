<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Verificar que la solicitud sea POST y que el nombre de usuario esté definido
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"])) {
    $username = $_POST["username"]; // Nombre de usuario que se va a eliminar

    // Si no hay un usuario válido, redirigir con error
    if (empty($username)) {
        header("Location: admin.php?error=Usuario no válido.");
        exit();
    }

    // Verificar si el usuario que hace la solicitud es un administrador o está eliminando su propio perfil
    if ($_SESSION["user"]["role"] !== "admin" && $_SESSION["user"]["username"] !== $username) {
        header("Location: admin.php?error=No tienes permiso para eliminar este usuario.");
        exit();
    }

    // URL de la API para eliminar al usuario
    $api_url = "http://localhost:8081/automarketuao/users/delete/" . urlencode($username);

    // Inicializamos cURL para hacer la solicitud DELETE
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Depuración: Guardar respuesta en un log (verificar si la API responde)
    file_put_contents("log.txt", "Response: $response\nHTTP Code: $http_code\n", FILE_APPEND);

    if ($http_code === 200) {
        // Redirigir según el rol del usuario
        if ($_SESSION["user"]["role"] === "admin") {
            header("Location: admin.php?success=Usuario eliminado correctamente.");
        } else {
            session_destroy();
            header("Location: index.php?success=Tu cuenta ha sido eliminada.");
        }
    } else {
        // Redirigir con error si la eliminación falla
        header("Location: admin.php?error=No se pudo eliminar el usuario. Código: $http_code");
    }
    exit();
} else {
    // Si la solicitud no es válida
    header("Location: admin.php?error=Solicitud inválida.");
    exit();
}

