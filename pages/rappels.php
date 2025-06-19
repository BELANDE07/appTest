<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappels - Assistant Personnel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <div class="col-md-3 bg-primary text-white p-4 sidebar">
                <div class="text-center mb-5">
                    <div id="avatar-animation" class="avatar-animation mb-3"></div>
                    <h2>Assistant IA</h2>
                    <p class="text-white-50">Votre compagnon intelligent</p>
                </div>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-home me-2"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/rappels.php">
                            <i class="fas fa-bell me-2"></i> Rappels
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/taches.php">
                            <i class="fas fa-tasks me-2"></i> Tâches
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/parametres.php">
                            <i class="fas fa-cog me-2"></i> Paramètres
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 p-4 main-content">
                <header class="d-flex justify-content-between align-items-center mb-5">
                    <h1 class="display-6"><i class="fas fa-bell me-2"></i>Gestion des Rappels</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newReminderModal">
                        <i class="fas fa-plus me-1"></i> Nouveau Rappel
                    </button>
                </header>

                <!-- Filtres -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Filtrer par date</label>
                                <input type="date" class="form-control" id="filter-date">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Statut</label>
                                <select class="form-select" id="filter-status">
                                    <option value="all">Tous</option>
                                    <option value="active">Actifs</option>
                                    <option value="completed">Terminés</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-outline-secondary w-100" id="reset-filters">
                                    <i class="fas fa-undo me-1"></i> Réinitialiser
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des rappels -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Mes Rappels</h5>
                        <span class="badge bg-primary" id="reminders-count">0</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date/Heure</th>
                                        <th>Message</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="reminders-table">
                                    <!-- Rempli dynamiquement -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nouveau Rappel -->
    <div class="modal fade" id="newReminderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau Rappel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reminder-form">
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" id="reminder-message" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" id="reminder-date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Heure</label>
                                <input type="time" class="form-control" id="reminder-time" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Répétition</label>
                            <select class="form-select" id="reminder-repeat">
                                <option value="none">Ne pas répéter</option>
                                <option value="daily">Quotidien</option>
                                <option value="weekly">Hebdomadaire</option>
                                <option value="monthly">Mensuel</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="save-reminder">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/reminders.js"></script>
</body>
</html>