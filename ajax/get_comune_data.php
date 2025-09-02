<?php
require_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['term'])) {
    echo json_encode(['error' => 'Missing parameter']);
    exit;
}

$term = $_GET['term'];

try {
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
