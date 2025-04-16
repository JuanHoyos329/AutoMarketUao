<?php
session_start();

if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["userId"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    $api_url = "http://localhost:3002/favoritos/$id";

    $options = [
        "http" => [
            "method" => "DELETE",
            "ignore_errors" => true
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($api_url, false, $context);

    // Puedes manejar errores aquí si lo deseas

    header("Location: favoritos.php");
    exit();
} else {
    header("Location: favoritos.php");
    exit();
}
?>