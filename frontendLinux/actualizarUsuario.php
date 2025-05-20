<?php  
session_start();

// Verificar si el usuario es administrador  
$isAdmin = ($_SESSION["user"]["role"] === "admin");

// Obtener el email del usuario a editar  
$emailToEdit = isset($_GET["email"]) && !empty($_GET["email"]) ? filter_var($_GET["email"], FILTER_SANITIZE_EMAIL) : $_SESSION["user"]["email"];

// URL del backend para obtener el usuario
$api_url = "http://192.168.100.3:8081/automarketuao/users/read/" . urlencode($emailToEdit);

// Función para obtener datos de la API  
function fetchFromApi($url) {  
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ["data" => json_decode($response, true), "http_code" => $http_code];
}

// Obtener información del usuario  
$userResponse = fetchFromApi($api_url);
$userToEdit = $userResponse["data"];

// Verifica si se obtuvieron datos del usuario
if (!$userToEdit) {  
    $_SESSION["mensaje"] = '<div class="alert alert-danger text-center">❌ Error: No se pudo obtener la información del usuario.</div>';
    header("Location: " . ($isAdmin ? "admin.php" : "perfil.php"));
    exit();
}

// Mensaje de éxito o error
$mensaje = $_SESSION["mensaje"] ?? "";
unset($_SESSION["mensaje"]);
$success = false; // Variable que indica si la actualización fue exitosa

// 🚀 Procesar actualización  
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {  
    $data = [];
    $allowedFields = ["name", "last_name", "email", "username", "phone", "password"];

    foreach ($allowedFields as $field) {
        if (!empty($_POST[$field])) {
            $data[$field] = $_POST[$field];
        }
    }

    if (!$isAdmin) {
        unset($data["role"]);
    }

    if (!empty($data)) {  
        $json_data = json_encode($data);  
        $update_url = "http://192.168.100.3:8081/automarketuao/users/update/" . urlencode($userToEdit['email']); 

        $ch = curl_init($update_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Decodificar respuesta
        $responseData = json_decode($response, true);

        if ($http_code == 200) {  
            $_SESSION["mensaje"] = '<div class="alert alert-success text-center">✅ Usuario actualizado correctamente.</div>';
            $success = true; // La actualización fue exitosa
            if ($_SESSION["user"]["email"] === $emailToEdit) {  
                $_SESSION["user"] = array_merge($_SESSION["user"], $data);  
            }  
        } else {  
            $errorMsg = isset($responseData["error"]) ? $responseData["error"] : "Error desconocido";
            $_SESSION["mensaje"] = '<div class="alert alert-danger text-center">❌ ' . htmlspecialchars($errorMsg) . '</div>';
        }

        header("Location: actualizarUsuario.php?email=" . urlencode($emailToEdit));
        exit();
    } else {  
        $_SESSION["mensaje"] = '<div class="alert alert-warning text-center">⚠ No se han realizado cambios.</div>';
        header("Location: actualizarUsuario.php?email=" . urlencode($emailToEdit));
        exit();
    }  
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4 text-primary">Editar Usuario</h2>

            <!-- Mensaje de éxito o error -->
            <?= $mensaje ?>

            <form action="actualizarUsuario.php?email=<?= urlencode($userToEdit['email']) ?>" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($userToEdit['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($userToEdit['last_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($userToEdit['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($userToEdit['phone']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Dejar en blanco si no deseas cambiarla">
                    <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar la contraseña.</small>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" name="update" class="btn btn-success">Actualizar</button>
                    <a href="<?= $isAdmin ? 'admin.php' : 'perfil.php' ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>