<?php
// Iniciar sesión y verificar permisos
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: index.php");
    exit();
}

// Verificar si se recibió el email y el nuevo rol
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"], $_POST["role"])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $role = $_POST["role"];

    // Validar que el rol sea uno de los valores permitidos
    if ($role !== "user" && $role !== "admin") {
        die('<div class="alert alert-danger text-center">❌ Error: Rol no válido.</div>');
    }

    // Aquí se debería hacer la llamada a la API o la base de datos para actualizar el rol del usuario
    $api_url = "http://192.168.100.3:8080/automarketuao/users/updateRole"; // Asumiendo que tienes una ruta para esto
    $data = json_encode(['email' => $email, 'role' => $role]);

    // Iniciar cURL para hacer la solicitud
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Usamos PUT para actualizar
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        // Redirigir después de cambiar el rol
        header("Location: admin.php");
        exit();
    } else {
        die('<div class="alert alert-danger text-center">❌ Error al cambiar el rol del usuario.</div>');
    }
} else {
    die('<div class="alert alert-danger text-center">❌ Error: Faltan datos para actualizar el rol.</div>');
}
