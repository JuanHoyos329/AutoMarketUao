<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$userId = $user["userId"]; // Asumiendo que el ID del usuario está en la sesión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Trámites | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4">Mis Trámites</h2>

            <div class="text-center mb-3">
                <button id="btnComprador" class="btn btn-primary">Trámites como Comprador</button>
                <button id="btnVendedor" class="btn btn-success">Trámites como Vendedor</button>
            </div>

            <div id="tramitesList" class="mt-4">
                <p class="text-center">Selecciona una opción para ver tus trámites.</p>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function cargarTramites(tipo) {
                let userId = <?= json_encode($userId) ?>;
                let url = tipo === 'comprador' ? `http://localhost:8082/api/tramites/comprador/${userId}` : `http://localhost:8082/api/tramites/vendedor/${userId}`;

                $.get(url, function(response) {
                    if (response.ok) {
                        let tramitesHTML = '<ul class="list-group">';
                        response.data.forEach(tramite => {
                            tramitesHTML += `<li class="list-group-item">
                                <strong>ID Trámite:</strong> ${tramite.id} <br>
                                <strong>Estado:</strong> ${tramite.estado} <br>
                                <strong>Fecha:</strong> ${tramite.fecha} <br>
                                <a href="verTramite.php?id=${tramite.id}" class="btn btn-info btn-sm mt-2">Ver Trámite</a>
                            </li>`;
                        });
                        tramitesHTML += '</ul>';
                        $("#tramitesList").html(tramitesHTML);
                    } else {
                        $("#tramitesList").html('<p class="text-center text-danger">No se encontraron trámites.</p>');
                    }
                }).fail(function() {
                    $("#tramitesList").html('<p class="text-center text-danger">Error al obtener los trámites.</p>');
                });
            }

            $("#btnComprador").click(function() {
                cargarTramites("comprador");
            });

            $("#btnVendedor").click(function() {
                cargarTramites("vendedor");
            });
        });
    </script>
</body>
</html>