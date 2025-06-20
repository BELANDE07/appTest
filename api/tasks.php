<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$tasksFile = __DIR__ . '/../data/tasks.json';

// Créer le fichier s'il n'existe pas
if (!file_exists($tasksFile)) {
    file_put_contents($tasksFile, json_encode([]));
}

// GET - Récupérer toutes les tâches ou une tâche spécifique
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tasks = json_decode(file_get_contents($tasksFile), true) ?: [];
    
    if (isset($_GET['id'])) {
        $taskId = $_GET['id'];
        $task = array_filter($tasks, function($t) use ($taskId) {
            return $t['id'] === $taskId;
        });
        echo json_encode(array_values($task)[0] ?? null);
    } else {
        echo json_encode($tasks);
    }
    exit;
}

// POST - Créer une nouvelle tâche
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (empty($input['title'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Le titre est obligatoire']);
        exit;
    }
    
    $tasks = json_decode(file_get_contents($tasksFile), true) ?: [];
    $newTask = [
        'id' => uniqid(),
        'title' => $input['title'],
        'description' => $input['description'] ?? '',
        'due_date' => $input['due_date'] ?? null,
        'priority' => $input['priority'] ?? 'medium',
        'category' => $input['category'] ?? '',
        'status' => $input['status'] ?? 'todo',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $tasks[] = $newTask;
    file_put_contents($tasksFile, json_encode($tasks));
    
    echo json_encode(['status' => 'success', 'task' => $newTask]);
    exit;
}

// PUT - Mettre à jour une tâche
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'ID manquant']);
        exit;
    }
    
    $taskId = $_GET['id'];
    $input = json_decode(file_get_contents('php://input'), true);
    $tasks = json_decode(file_get_contents($tasksFile), true) ?: [];
    
    $updated = false;
    foreach ($tasks as &$task) {
        if ($task['id'] === $taskId) {
            $task = array_merge($task, $input);
            $task['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }
    
    if ($updated) {
        file_put_contents($tasksFile, json_encode($tasks));
        echo json_encode(['status' => 'success', 'task' => $task]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Tâche non trouvée']);
    }
    exit;
}

// DELETE - Supprimer une tâche
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'ID manquant']);
        exit;
    }
    
    $taskId = $_GET['id'];
    $tasks = json_decode(file_get_contents($tasksFile), true) ?: [];
    $initialCount = count($tasks);
    
    $tasks = array_filter($tasks, function($task) use ($taskId) {
        return $task['id'] !== $taskId;
    });
    
    if (count($tasks) < $initialCount) {
        file_put_contents($tasksFile, json_encode(array_values($tasks)));
        echo json_encode(['status' => 'success', 'message' => 'Tâche supprimée']);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Tâche non trouvée']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);