<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tâches - Assistant Personnel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

        <?php include('../pages/header.php'); ?>
            
            <!-- Main Content -->
            <div class="col-md-9 p-4 main-content">
                <header class="d-flex justify-content-between align-items-center mb-5">
                    <h1 class="display-6"><i class="fas fa-tasks me-2"></i>Gestion des Tâches</h1>
                    <div>
                        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#newTaskModal">
                            <i class="fas fa-plus me-1"></i> Nouvelle Tâche
                        </button>
                        <button class="btn btn-outline-primary" id="voice-task-btn">
                            <i class="fas fa-microphone me-1"></i> Commande Vocale
                        </button>
                    </div>
                </header>

                <!-- Tableau Kanban -->
                <div class="row">
                    <!-- Colonne À Faire -->
                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">À Faire</h5>
                            </div>
                            <div class="card-body p-2" id="todo-tasks">
                                <!-- Tâches à faire -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne En Cours -->
                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">En Cours</h5>
                            </div>
                            <div class="card-body p-2" id="inprogress-tasks">
                                <!-- Tâches en cours -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne Terminé -->
                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Terminé</h5>
                            </div>
                            <div class="card-body p-2" id="done-tasks">
                                <!-- Tâches terminées -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h3 class="text-info" id="todo-count">0</h3>
                                <p class="mb-0">À Faire</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h3 class="text-warning" id="inprogress-count">0</h3>
                                <p class="mb-0">En Cours</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h3 class="text-success" id="done-count">0</h3>
                                <p class="mb-0">Terminé</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nouvelle Tâche -->
    <div class="modal fade" id="newTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle Tâche</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="task-form">
                        <div class="mb-3">
                            <label class="form-label">Titre</label>
                            <input type="text" class="form-control" id="task-title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="task-description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date d'échéance</label>
                                <input type="date" class="form-control" id="task-due-date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Priorité</label>
                                <select class="form-select" id="task-priority">
                                    <option value="low">Basse</option>
                                    <option value="medium" selected>Moyenne</option>
                                    <option value="high">Haute</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <input type="text" class="form-control" id="task-category">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="save-task">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="notification-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body d-flex justify-content-between align-items-center">
            <span class="toast-message"></span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="../assets/js/tasks.js"></script>
    <script src="../assets/js/index.js"></script>
</body>
</html>