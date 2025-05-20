<?php
session_start();
$idComprador = $_SESSION['id_usuario'];

// Obtener los trámites desde la base de datos
$tramites = []; // Consulta la base de datos aquí

?>

<h2>Mis Trámites</h2>
<table>
    <tr>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Año</th>
        <th>Precio</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($tramites as $tramite): ?>
    <tr>
        <td><?= $tramite['marca'] ?></td>
        <td><?= $tramite['modelo'] ?></td>
        <td><?= $tramite['ano'] ?></td>
        <td>$<?= number_format($tramite['precio'], 2) ?></td>
        <td><?= $tramite['estado'] ?></td>
        <td><button onclick="verDetalles(<?= $tramite['id'] ?>)">Más Información</button></td>
    </tr>
    <?php endforeach; ?>
</table>

<script>
function verDetalles(idTramite) {
    window.location.href = "detalle_tramite.php?id=" + idTramite;
}
</script><?php
session_start();
$idComprador = $_SESSION['id_usuario'];

// Obtener los trámites desde la base de datos
$tramites = []; // Consulta la base de datos aquí

?>

<h2>Mis Trámites</h2>
<table>
    <tr>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Año</th>
        <th>Precio</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($tramites as $tramite): ?>
    <tr>
        <td><?= $tramite['marca'] ?></td>
        <td><?= $tramite['modelo'] ?></td>
        <td><?= $tramite['ano'] ?></td>
        <td>$<?= number_format($tramite['precio'], 2) ?></td>
        <td><?= $tramite['estado'] ?></td>
        <td><button onclick="verDetalles(<?= $tramite['id'] ?>)">Más Información</button></td>
    </tr>
    <?php endforeach; ?>
</table>

<script>
function verDetalles(idTramite) {
    window.location.href = "detalle_tramite.php?id=" + idTramite;
}
</script>
