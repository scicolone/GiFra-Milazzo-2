<?php
$host = 'sql.giovannicusumano.it';
$dbname = 'giovanni26877';
$username = 'giovanni26877';
$password = 'giov37810';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Errore di connessione: " . $e->getMessage());
}
?>
