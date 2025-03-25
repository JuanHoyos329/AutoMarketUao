<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"])) {
    $username = $_POST["username"]; // Nombre de usuario que se va a eliminar

    // Verificar si el usuario que hace la solicitud es un administrador o está eliminando su propio perfil
    if ($_SESSION["user"]["role"] !== "admin" && $_SESSION["user"]["username"] !== $username) {
        // Si no es admin y no está eliminando su propio perfil, redirigimos con error
        header("Location: admin.php?error=No tienes permiso para eliminar este usuario.");
        exit();
    }

    // Verificar si el usuario que se va a eliminar tiene el rol de admin
    if ($_SESSION["user"]["role"] === "admin" && $username === $_SESSION["user"]["username"]) {
        // Si el usuario es admin, mostrar error
        header("Location: admin.php?error=No puedes eliminar a un usuario administrador.");
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

    if ($http_code === 200) {
        // Redirigir a la página admin.php con un mensaje de éxito
        // Si es un administrador, lo redirige a la página de administración
        // Si es el usuario normal, lo redirige a la página de inicio de sesión (index.php)
        if ($_SESSION["user"]["role"] === "admin") {
            header("Location: admin.php?success=Usuario eliminado correctamente.");
        } else {
            // Si es un usuario normal, cerramos la sesión y lo redirigimos a la página de inicio
            session_destroy();
            header("Location: index.php?success=Tu cuenta ha sido eliminada.");
        }
    } else {
        // En caso de error, redirigir a admin.php con un mensaje de error
        header("Location: admin.php?error=No se pudo eliminar el usuario o el usuario es un administrador.");
    }
    exit();
} else {
    // Si la solicitud no es válida
    header("Location: admin.php?error=Solicitud inválida.");
    exit();
}
?>
