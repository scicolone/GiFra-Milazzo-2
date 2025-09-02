<?php
header('Content-Type: application/json');
require_once '../config.php';

$term = $_GET['term'] ?? '';
$type = $_GET['type'] ?? 'nascita';

if (empty($term)) {
    echo json_encode(['provincia' => '', 'cap' => '', 'codice_catastale' => '']);
    exit;
}

// Cerca comuni che iniziano con il termine (case insensitive)
$stmt = $pdo->prepare("
    SELECT provincia, cap, codice_catastale 
    FROM comuni 
    WHERE nome LIKE ? 
    ORDER BY nome 
    LIMIT 1
");
$searchTerm = $term . '%';
$stmt->execute([$searchTerm]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(['provincia' => '', 'cap' => '', 'codice_catastale' => '']);
}
?>
