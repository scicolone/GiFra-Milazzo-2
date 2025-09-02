<?php
header('Content-Type: application/json');
require_once '../config.php';

$comune = $_GET['comune'] ?? '';

if (empty($comune)) {
    echo json_encode(['provincia' => '', 'cap' => '', 'codice_catastale' => '']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT provincia, cap, codice_catastale 
    FROM comuni 
    WHERE nome = ? 
    LIMIT 1
");
$stmt->execute([$comune]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(['provincia' => '', 'cap' => '', 'codice_catastale' => '']);
}
?>
