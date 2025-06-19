<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Chemin vers le fichier de stockage
$remindersFile = __DIR__ . '/../data/reminders.json';

// Créer le fichier et le dossier s'ils n'existent pas
if (!file_exists(dirname($remindersFile))) {
    mkdir(dirname($remindersFile), 0777, true);
}

if (!file_exists($remindersFile)) {
    file_put_contents($remindersFile, json_encode([]));
}

// GET - Récupérer tous les rappels
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $reminders = json_decode(file_get_contents($remindersFile), true) ?: [];
    
    // Filtrer par ID si spécifié
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $reminder = array_filter($reminders, function($r) use ($id) {
            return $r['id'] === $id;
        });
        echo json_encode(array_values($reminder)[0] ?? []);
        exit;
    }
    
    echo json_encode($reminders);
    exit;
}

// POST - Ajouter un rappel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['time']) || !isset($input['message'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
        exit;
    }
    
    $reminders = json_decode(file_get_contents($remindersFile), true) ?: [];
    $newReminder = [
        'id' => uniqid(),
        'time' => $input['time'],
        'message' => $input['message'],
        'created_at' => date('Y-m-d H:i:s'),
        'is_completed' => false
    ];
    
    $reminders[] = $newReminder;
    file_put_contents($remindersFile, json_encode($reminders));
    
    echo json_encode(['status' => 'success', 'reminder' => $newReminder]);
    exit;
}

// DELETE - Supprimer un rappel
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'ID manquant']);
        exit;
    }
    
    $id = $_GET['id'];
    $reminders = json_decode(file_get_contents($remindersFile), true) ?: [];
    $initialCount = count($reminders);
    
    $reminders = array_filter($reminders, function($reminder) use ($id) {
        return $reminder['id'] !== $id;
    });
    
    if (count($reminders) < $initialCount) {
        file_put_contents($remindersFile, json_encode(array_values($reminders)));
        echo json_encode(['status' => 'success', 'message' => 'Rappel supprimé']);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Rappel non trouvé']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);