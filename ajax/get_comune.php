<?php
header('Content-Type: application/json');

$term = $_GET['term'] ?? '';
$tipo = $_GET['tipo'] ?? 'comune'; // 'comune' o 'luogo'

if (empty($term)) {
    echo json_encode([]);
    exit;
}

// Dati di esempio - in produzione usa database
$dati = [
    ['nome' => 'Milazzo', 'provincia' => 'ME', 'cap' => '98056'],
    ['nome' => 'Messina', 'provincia' => 'ME', 'cap' => '98100'],
    ['nome' => 'Palermo', 'provincia' => 'PA', 'cap' => '90100'],
    ['nome' => 'Catania', 'provincia' => 'CT', 'cap' => '95100'],
    ['nome' => 'Roma', 'provincia' => 'RM', 'cap' => '00100'],
    ['nome' => 'Milano', 'provincia' => 'MI', 'cap' => '20100']
];

$results = [];
$term = strtoupper($term);

foreach ($dati as $dato) {
    if (strpos(strtoupper($dato['nome']), $term) !== false) {
        $results[] = $dato;
    }
}

echo json_encode($results);
?>
