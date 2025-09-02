<?php
header('Content-Type: application/json');
require_once '../config.php';

$term = $_GET['term'] ?? '';

if (empty($term)) {
    echo json_encode(['provincia' => '', 'cap' => '', 'codice_catastale' => '']);
    exit;
}

// Cerca il comune esatto (case insensitive)
$stmt = $pdo->prepare("
    SELECT provincia, cap, codice_catastale 
    FROM comuni 
    WHERE UPPER(nome) = UPPER(?)
    LIMIT 1
");
$stmt->execute([$term]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(['provincia' => '', 'cap' => '', 'codice_catastale' => '']);
}
?>
