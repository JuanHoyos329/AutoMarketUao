<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    die("Error: No se proporcionó un ID de trámite.");
}

$tramiteId = $_GET["id"];
$apiUrl = "http://192.168.100.3:8082/api/tramites/" . $tramiteId;

$response = file_get_contents($apiUrl);
$tramite = json_decode($response, true);

if (!$tramite || !$tramite["ok"]) {
    die("Error al obtener la información del trámite.");
}

$tramiteData = $tramite["data"];
$user = $_SESSION["user"];
$isVendedor = ($user["userId"] == $tramiteData["id_vendedor"]);
$isComprador = ($user["userId"] == $tramiteData["id_comprador"]);

$pasos = [
    "revision_doc" => "Revisión de Documentos",
    "cita" => "Cita Programada",
    "contrato" => "Contrato Firmado",
    "pago" => "Pago Realizado",
    "Traspaso" => "Traspaso de Propiedad",
    "entrega" => "Entrega del Vehículo"
];

$descripciones = [
    "revision_doc" => "Vendedor y Comprador verifican que ambos presenten documentos legales en regla y que el vehículo no tenga problemas legales o administrativos. Los documentos incluyen título de propiedad, identificación oficial, tarjeta de circulación, comprobantes de pago, seguro vigente, historial del vehículo, carta de no adeudo y más.",
    "cita" => "Vendedor y Comprador acuerdan un encuentro para inspeccionar el vehículo, verificar su estado, revisar la documentación, hacer preguntas y coordinar una prueba de manejo.",
    "contrato" => "Ambas partes firman un contrato de compra-venta que detalla los términos del acuerdo, incluyendo datos de ambas partes, del vehículo, precio, método de pago, condiciones de entrega y responsabilidades.",
    "pago" => "El comprador realiza el pago de acuerdo con lo estipulado en el contrato, recomendando usar métodos seguros, solicitar un recibo y evitar pagos en efectivo sin testigos.",
    "Traspaso" => "Vendedor y Comprador acuden a la entidad gubernamental correspondiente para formalizar el cambio de propietario en el registro vehicular.",
    "entrega" => "El vendedor hace la entrega oficial del automóvil al comprador, incluyendo la documentación original y verificando nuevamente el estado del vehículo."
];

$habilitarSiguiente = true;

$fechaInicio = "Fecha no disponible";
if (!empty($tramiteData["fecha_inicio"])) {
    $fecha = DateTime::createFromFormat("Y-m-d\TH:i:s.u\Z", $tramiteData["fecha_inicio"]);
    if ($fecha) {
        setlocale(LC_TIME, "es_ES.UTF-8");
        $fechaInicio = strftime("%d de %B de %Y, %I:%M %p", $fecha->getTimestamp());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Trámite</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .small-button {
            padding: 1px 3px; /* Tamaño reducido */
            font-size: 0.5em; /* Fuente más pequeña */
            background-color: #e0e0e0; /* Color gris claro */
            border-color: #e0e0e0; /* Bordes grises claros */
            color: black; /* Texto en negro */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4">Detalles del Trámite</h2>
            <ul class="list-group">
                <li class="list-group-item"><strong>Fecha de inicio:</strong> <?= htmlspecialchars($fechaInicio) ?></li>
                <li class="list-group-item"><strong>Vehículo:</strong> <?= htmlspecialchars($tramiteData["marca"] . " " . $tramiteData["modelo"] . " " . $tramiteData["ano"]) ?></li>
                <li class="list-group-item"><strong>Precio:</strong> $<?= htmlspecialchars($tramiteData["precio"]) ?></li>
                <li class="list-group-item"><strong>Vendedor:</strong> <?= htmlspecialchars($tramiteData["user_vendedor"]) ?> | <strong>Tel:</strong> <?= htmlspecialchars($tramiteData["tel_vendedor"]) ?> | <strong>Email:</strong> <?= htmlspecialchars($tramiteData["email_vendedor"]) ?></li>
                <li class="list-group-item"><strong>Comprador:</strong> <?= htmlspecialchars($tramiteData["user_comprador"]) ?> | <strong>Tel:</strong> <?= htmlspecialchars($tramiteData["tel_comprador"]) ?> | <strong>Email:</strong> <?= htmlspecialchars($tramiteData["email_comprador"]) ?></li>
            </ul>
            
            <h4 class="mt-4">Proceso del Trámite</h4>
            <p class="text-danger"><strong>Nota:</strong> Advertencia: Si completas un paso no podrás devolver esta acción. Asegúrate de tener una buena comunicación con tu comprador o vendedor para evitar errores o confusiones.</p>
            <ul class="list-group">
                <?php foreach ($pasos as $clave => $nombre): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <b class="me-2"><?= $nombre ?></b>
                            <button class="btn btn-sm small-button" data-bs-toggle="collapse" data-bs-target="#<?= $clave ?>Info">❓</button>
                        </div>
                        <div id="<?= $clave ?>Info" class="collapse mt-2 w-100">
                            <p><?= $descripciones[$clave] ?></p>
                        </div>
                        <div class="d-flex align-items-center">
                            <?php if ($tramiteData[$clave]): ?>
                                <span class="badge bg-success ms-2">Completado</span>
                            <?php else: ?>
                                <?php if ($isVendedor): ?>
                                    <button class="btn btn-primary btn-sm actualizarPaso ms-2" data-id="<?= $tramiteId ?>" data-paso="<?= $clave ?>" <?= ($habilitarSiguiente ? '' : 'disabled') ?>>Marcar como completado</button>
                                    <?php $habilitarSiguiente = false; ?>
                                <?php elseif ($isComprador): ?>
                                    <span class="badge bg-warning text-dark ms-2">Pendiente</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Botones colocados uno al lado del otro -->
            <div class="text-center mt-4">
                <a href="misTramites.php" class="btn btn-secondary me-2">Volver a Mis Trámites</a>
                <button id="cancelarTramite" class="btn btn-danger" data-id="<?= $tramiteId ?>">Cancelar Trámite</button>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            $(".actualizarPaso").click(function() {
                let btn = $(this);
                let tramiteId = btn.data("id");
                let paso = btn.data("paso");
                let userId = <?= json_encode($user["userId"]) ?>;
                
                $.ajax({
                    url: `http://192.168.100.3:8082/api/tramites/paso/${tramiteId}`,
                    type: "PUT",
                    contentType: "application/json",
                    data: JSON.stringify({ pasoActualizar: paso, id_usuario: userId }),
                    success: function(response) {
                        alert("Paso actualizado correctamente.");
                        location.reload();
                    },
                    error: function(xhr) {
                        alert(xhr.responseText || "Error desconocido al actualizar el trámite.");
                    }
                });
            });

            // Lógica del botón para cancelar el trámite
            $("#cancelarTramite").click(function() {
                let tramiteId = $(this).data("id");
                let userId = <?= json_encode($user["userId"]) ?>;

                if (!confirm("¿Estás seguro de que deseas cancelar este trámite? Esta acción no se puede deshacer.")) {
                    return;
                }

                $.ajax({
                    url: `http://192.168.100.3:8082/api/tramites/cancel/${tramiteId}`,
                    type: "PUT",
                    contentType: "application/json",
                    data: JSON.stringify({ id_usuario: userId }),
                    success: function(response) {
                        alert("Trámite cancelado exitosamente.");
                        window.location.href = "misTramites.php"; // Redireccionar al usuario
                    },
                    error: function(xhr) {
                        alert(xhr.responseText || "Error desconocido al cancelar el trámite.");
                    }
                });
            });
        });
    </script>
</body>
</html>
