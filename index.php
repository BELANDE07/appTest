<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistant Personnel IA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Lottie -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.10.2/lottie.min.js"></script>
    <!-- Style personnalisé -->
    <link rel="stylesheet" href="assets/css/style.css">
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
                    <h1 class="display-6">Bonjour, comment puis-je vous aider ?</h1>
                    <div id="voice-status" class="badge bg-success">
                        <i class="fas fa-microphone me-1"></i> Prêt
                    </div>
                </header>
                
                <!-- Voice Command Section -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body text-center">
                        <div id="voice-animation" class="voice-animation mx-auto mb-3"></div>
                        <button id="voice-command-btn" class="btn btn-danger btn-lg rounded-circle pulse">
                            <i class="fas fa-microphone"></i>
                        </button>
                        <p class="mt-3 text-muted">Cliquez pour parler à votre assistant</p>
                        <div id="voice-feedback" class="alert alert-info mt-3 d-none"></div>
                    </div>
                </div>
                
                <!-- Reminder Form -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Nouveau Rappel</h5>
                            </div>
                            <div class="card-body">
                                <form id="reminder-form">
                                    <div class="mb-3">
                                        <label for="reminder-time" class="form-label">Heure</label>
                                        <input type="time" class="form-control" id="reminder-time" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="reminder-date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="reminder-date">
                                    </div>
                                    <div class="mb-3">
                                        <label for="reminder-text" class="form-label">Message</label>
                                        <textarea class="form-control" id="reminder-text" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-2"></i>Enregistrer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reminders List -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Mes Rappels</h5>
                                <span class="badge bg-primary" id="reminders-count">0</span>
                            </div>
                            <div class="card-body">
                                <ul class="list-group" id="reminders-container">
                                    <!-- Les rappels seront chargés ici -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="notification-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script personnalisé -->
    <script src="assets/js/script.js"></script>
</body>
</html>