<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

$settingsFile = __DIR__ . '/../data/settings.json';

// Créer le fichier s'il n'existe pas
if (!file_exists($settingsFile)) {
    file_put_contents($settingsFile, json_encode([]));
}

// GET - Récupérer les paramètres
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $settings = json_decode(file_get_contents($settingsFile), true) ?: [];
    echo json_encode($settings);
    exit;
}

// POST/PUT - Sauvegarder les paramètres
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (empty($input)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
        exit;
    }
    
    $currentSettings = json_decode(file_get_contents($settingsFile), true) ?: [];
    $newSettings = array_merge($currentSettings, $input);
    
    file_put_contents($settingsFile, json_encode($newSettings));
    echo json_encode(['status' => 'success', 'settings' => $newSettings]);
    exit;
}

http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);