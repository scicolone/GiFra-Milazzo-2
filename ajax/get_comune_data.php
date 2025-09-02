<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Connessione diretta al database
$host = 'm-51.th.seeweb.it';
$dbname = 'giovanni90252';
$username = 'giovanni90252';
$password = 'tua_password'; // Inserisci la password corretta

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (!isset($_GET['term'])) {
        echo json_encode(['error' => 'Missing parameter']);
        exit;
    }
    
    $term = $_GET['term'];
    
    $stmt = $pdo->prepare("
        SELECT nome, provincia, cap, codice_catastale 
        FROM comuni 
        WHERE nome = ? 
        LIMIT 1
    ");
    $stmt->execute([$term]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'Comune non trovato']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
?>
