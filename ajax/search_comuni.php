<?php
header('Content-Type: application/json');
require_once '../config.php';

$term = $_GET['term'] ?? '';

if (empty($term)) {
    echo json_encode([]);
    exit;
}

// Cerca comuni che contengono il termine
$stmt = $pdo->prepare("
    SELECT nome, provincia, cap 
    FROM comuni 
    WHERE nome LIKE ? 
    ORDER BY nome 
    LIMIT 10
");
$searchTerm = '%' . $term . '%';
$stmt->execute([$searchTerm]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>
