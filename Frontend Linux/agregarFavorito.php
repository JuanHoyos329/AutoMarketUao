<?php
session_start();

if (!isset($_SESSION["user"]) || !isset($_SESSION["user"]["userId"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["idPublicacion"])) {
    $userId = $_SESSION["user"]["userId"];
    $idPublicacion = $_POST["idPublicacion"];

    $api_url = "http://localhost:3002/favoritos";
    $data = [
        "userId" => $userId,
        "idPublicacion" => $idPublicacion
    ];

    $options = [
        "http" => [
            "header"  => "Content-type: application/json",
            "method"  => "POST",
            "content" => json_encode($data),
            "ignore_errors" => true
        ]
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($api_url, false, $context);

    // Puedes manejar errores aquí si lo deseas
    // $response = json_decode($result, true);

    header("Location: publicaciones.php");
    exit();
} else {
    header("Location: publicaciones.php");
    exit();
}
?>