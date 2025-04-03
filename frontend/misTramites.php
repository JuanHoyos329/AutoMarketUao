<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$userId = $user["userId"];
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
                <button id="btnFinalizados" class="btn btn-danger">Cancelados/Finalizados</button>
            </div>
            
            <div id="tramitesList" class="mt-4">
                <p class="text-center">Selecciona una opción para ver tus trámites.</p>
            </div>

            <div class="text-center mt-4">
                <a href="perfil.php" class="btn btn-secondary">Volver a Mi Perfil</a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function formatearFecha(fechaISO) {
                if (!fechaISO) return "Fecha no disponible";
                let fecha = new Date(fechaISO);
                return fecha.toLocaleString("es-ES", {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit"
                });
            }

            function obtenerPasoActual(tramite) {
                let pasos = [
                    { clave: "revision_doc", nombre: "Revisar Documentos" },
                    { clave: "cita", nombre: "Programar Cita" },
                    { clave: "contrato", nombre: "Firmar Contrato" },
                    { clave: "pago", nombre: "Realizar Pago" },
                    { clave: "Traspaso", nombre: "Traspaso de Propiedad" },
                    { clave: "entrega", nombre: "Entregar Vehículo" }
                ];

                let ultimoPasoIndex = -1;
                pasos.forEach((paso, index) => {
                    if (tramite[paso.clave]) {
                        ultimoPasoIndex = index;
                    }
                });

                if (ultimoPasoIndex === -1) {
                    return "Revisar Documentos";
                }
                if (ultimoPasoIndex === pasos.length - 1) {
                    return "Trámite Finalizado";
                }
                return pasos[ultimoPasoIndex + 1].nombre;
            }

            function cargarTramites(tipo) {
                let userId = <?= json_encode($userId) ?>;
                let urls = {
                    comprador: `http://localhost:8082/api/tramites/comprador/${userId}`,
                    vendedor: `http://localhost:8082/api/tramites/vendedor/${userId}`
                };

                if (tipo === "finalizados") {
                    let promesaComprador = $.get(urls.comprador);
                    let promesaVendedor = $.get(urls.vendedor);

                    Promise.all([promesaComprador, promesaVendedor]).then(responses => {
                        let tramites = [];
                        responses.forEach(response => {
                            if (response && response.ok && response.data) {
                                tramites = tramites.concat(response.data);
                            }
                        });

                        let tramitesFiltrados = tramites.filter(tramite => 
                            tramite.estado.toLowerCase() === "cancelado" || tramite.estado.toLowerCase() === "finalizado"
                        );
                        
                        mostrarTramites(tramitesFiltrados);
                    }).catch(() => {
                        $("#tramitesList").html('<p class="text-center text-danger">Error al obtener los trámites.</p>');
                    });
                } else {
                    $.get(urls[tipo], function(response) {
                        if (!response || !response.ok || !response.data) {
                            $("#tramitesList").html('<p class="text-center text-danger">No se encontraron trámites.</p>');
                            return;
                        }

                        let tramitesFiltrados = response.data.filter(tramite => tramite.estado.toLowerCase() === "activo");
                        mostrarTramites(tramitesFiltrados);
                    }).fail(function() {
                        $("#tramitesList").html('<p class="text-center text-danger">Error al obtener los trámites.</p>');
                    });
                }
            }

            function mostrarTramites(tramites) {
                if (tramites.length === 0) {
                    $("#tramitesList").html('<p class="text-center text-dark">No tienes trámites en esta categoría.</p>');
                    return;
                }

                let tramitesHTML = '<ul class="list-group">';
                tramites.forEach(tramite => {
                    let vehiculoInfo = `${tramite.marca} ${tramite.modelo} (${tramite.ano})`;
                    let precioInfo = tramite.precio ? `$${tramite.precio}` : "Precio no disponible";
                    let pasoActual = obtenerPasoActual(tramite);
                    let estadoTramite = tramite.estado || "Desconocido";
                    let extraInfo = `<strong>Vendedor:</strong> ${tramite.user_vendedor} <br>
                                     <strong>Comprador:</strong> ${tramite.user_comprador}`;

                    tramitesHTML += `<li class="list-group-item">
                        <strong>Vehículo:</strong> ${vehiculoInfo} <br>
                        <strong>Precio:</strong> ${precioInfo} <br>
                        ${extraInfo} <br>
                        <strong>Fecha de inicio:</strong> ${formatearFecha(tramite.fecha_inicio)} <br>
                        <strong>Siguiente Paso:</strong> ${pasoActual} <br>
                        <strong>Estado del Trámite:</strong> ${estadoTramite} <br>
                        <a href="verTramite.php?id=${tramite.id}" class="btn btn-info btn-sm mt-2">Ver Trámite</a>
                    </li>`;
                });
                tramitesHTML += '</ul>';
                $("#tramitesList").html(tramitesHTML);
            }

            $("#btnComprador").click(() => cargarTramites("comprador"));
            $("#btnVendedor").click(() => cargarTramites("vendedor"));
            $("#btnFinalizados").click(() => cargarTramites("finalizados"));
        });
    </script>
</body>
</html>