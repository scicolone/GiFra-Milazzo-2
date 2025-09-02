<?php
header('Content-Type: application/json');
require_once '../config.php';

$comune = $_GET['comune'] ?? '';

if (empty($comune)) {
    echo json_encode(['provincia' => '', 'cap' => '']);
    exit;
}

// Simulazione dati comuni (in produzione usa una tabella o API)
$comuni = [
    'Milazzo' => ['provincia' => 'ME', 'cap' => '98056'],
    'Messina' => ['provincia' => 'ME', 'cap' => '98100'],
    'Palermo' => ['provincia' => 'PA', 'cap' => '90133'],
    'Catania' => ['provincia' => 'CT', 'cap' => '95123']
];

$comune = strtoupper($comune);
$result = $comuni[$comune] ?? ['provincia' => '', 'cap' => ''];

echo json_encode($result);
?>
