<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"], $_POST["role"])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $role = $_POST["role"];

    if ($role !== "user" && $role !== "admin") {
        die('<div class="alert alert-danger text-center">❌ Error: Rol no válido.</div>');
    }

    $api_url = "http://192.168.100.3:8081/automarketuao/users/updateRole";
    $data = json_encode(['email' => $email, 'role' => $role]);

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $_SESSION["mensaje"] = '<div class="alert alert-success text-center">✅ Rol actualizado correctamente.</div>';
        header("Location: admin.php");
        exit();
    } else {
        $_SESSION["mensaje"] = '<div class="alert alert-danger text-center">❌ Error al cambiar el rol.</div>';
        header("Location: admin.php");
        exit();
    }
} else {
    $_SESSION["mensaje"] = '<div class="alert alert-danger text-center">❌ Error: Datos incompletos.</div>';
    header("Location: admin.php");
    exit();
}
