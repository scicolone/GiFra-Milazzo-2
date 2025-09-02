<?php
require_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['term']) || strlen($_GET['term']) < 2) {
    echo json_encode([]);
    exit;
}

$term = $_GET['term'] . '%';

try {
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
