<?php
// Other/connexio_api.php
$servidor = '127.0.0.1'; // ✅ no uses la IP 192.168.1.144
$bd = 'plataforma_videojocs';
$usuari = 'plataforma_user';
$contrasenya = '123456789a';

try {
    // ✅ No hace falta port, ni nada más
    $pdo = new PDO("mysql:host=$servidor;dbname=$bd;charset=utf8mb4", $usuari, $contrasenya);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de connexió: " . $e->getMessage();
    exit();
}
?>
