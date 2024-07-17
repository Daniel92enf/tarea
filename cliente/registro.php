<?php
    require_once "Rutas.php";
    $rutas = new Rutas();
    $errorMessage = '';
    $successMessage = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user'])) {
        $url = $rutas->dameUrlBase().'/servidor/index.php?action=registro';
        $data = array('user' => $_POST['user'], 'password' => $_POST['password']);
        $postdata = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        
        $response = json_decode($result, true);

        if (isset($response['status']) && $response['status'] === 'error') {
            $errorMessage = $response['message'];
        } elseif (isset($response['status']) && $response['status'] === 'success') {
            $successMessage = $response['message'];
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Inicio de Sesión</title>
</head>
<body>
<?php echo $rutas->dameMenuInicio()."&nbsp;&nbsp;&nbsp;&nbsp;".$rutas->dameMenuNuevo(); ?>
<br>
<form action="" method="post">
    <label for="usuario">Usuario:</label>
    <input type="text" id="usuario" name="user" required><br><br>
    <label for="contrasena">Contraseña:</label>
    <input type="password" id="contrasena" name="password" required><br><br>
    <input type="submit" value="Registrar">
</form>
<?php if ($errorMessage): ?>
    <div id="error-message" style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>
<?php if ($successMessage): ?>
    <div id="success-message" style="color: green;"><?php echo htmlspecialchars($successMessage); ?></div>
<?php endif; ?>
</body>
</html>
