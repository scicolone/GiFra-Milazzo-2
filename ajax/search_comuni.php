<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Connessione diretta al database se config.php non è incluso
$host = 'm-51.th.seeweb.it';
$dbname = 'giovanni90252';
$username = 'giovanni90252';
$password = 'tua_password'; // Inserisci la password corretta

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (!isset($_GET['term']) || strlen($_GET['term']) < 2) {
        echo json_encode([]);
        exit;
    }
    
    $term = $_GET['term'] . '%';
    
    $stmt = $pdo->prepare("
        SELECT nome, provincia, cap, codice_catastale 
        FROM comuni 
        WHERE nome LIKE ? 
        ORDER BY nome ASC 
        LIMIT 10
    ");
    $stmt->execute([$term]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($results);
    
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
