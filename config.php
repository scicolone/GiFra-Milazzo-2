<?php
$host = 'sql.giovannicusumano.it';
$dbname = 'giovanni90252';
$username = 'giovanni90252';
$password = 'Calimije@ns12';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Errore di connessione: " . $e->getMessage());
}
?>
