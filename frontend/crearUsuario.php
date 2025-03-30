<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario | AutoMarketUAO</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4">Crear Usuario</h2>
            
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = [
                    "name" => $_POST["name"],
                    "last_name" => $_POST["last_name"],
                    "email" => $_POST["email"],
                    "username" => $_POST["username"],
                    "password" => $_POST["password"], // Eliminada la encriptación
                    "phone" => $_POST["phone"]
                ];

                $json_data = json_encode($data);
                $api_url = "http://localhost:8081/automarketuao/users/create";

                $ch = curl_init($api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($http_code == 201) {
                    echo '<div class="alert alert-success text-center">✅ Usuario creado exitosamente.</div>';
                    header("Refresh: 2; URL=index.php");
                    exit();
                } else {
                    echo '<div class="alert alert-danger text-center">❌ Error al crear el usuario. Verifica los datos ingresados.</div>';
                }
            }
            ?>

            <form action="crearUsuario.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" placeholder="Ingrese su nombre" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="last_name" class="form-control" placeholder="Ingrese su apellido" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="email" class="form-control" placeholder="Ingrese su correo" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="username" class="form-control" placeholder="Ingrese su usuario" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="Ingrese su contraseña" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control" placeholder="Ingrese su teléfono" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success w-100">Crear Usuario</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
