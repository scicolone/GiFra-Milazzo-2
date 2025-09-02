<?php
header('Content-Type: application/json');
require_once '../config.php';

$term = $_GET['term'] ?? '';
$type = $_GET['type'] ?? 'comune'; // 'nascita' o 'residenza'

if (empty($term)) {
    echo json_encode([]);
    exit;
}

// Cerca comuni che iniziano con il termine
$stmt = $pdo->prepare("
    SELECT nome, provincia, cap, codice_catastale 
    FROM comuni 
    WHERE nome LIKE ? 
    ORDER BY nome 
    LIMIT 10
");
$stmt->execute([$term . '%']);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>
