<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistant IA - Gestion des Tâches</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Animation pour l'avatar */
        .avatar-animation {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(45deg, #0d6efd, #00ff88);
            animation: gradient 5s ease infinite;
            background-size: 200% 200%;
            margin: 0 auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Style de base */
        .sidebar {
            min-height: 100vh;
            transition: all 0.3s;
        }

        .nav-link {
            border-radius: 5px;
            margin-bottom: 5px;
            transition: all 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        /* Style pour le header mobile */
        .mobile-header {
            display: none;
            background-color: #0d6efd;
            padding: 10px 15px;
            align-items: center;
            justify-content: space-between;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            background: linear-gradient(45deg, #0d6efd, #00ff88);
        }

        .logo-text {
            color: white;
            font-weight: bold;
            margin: 0;
        }

        .hamburger-btn {
            color: white;
            font-size: 1.5rem;
            background: none;
            border: none;
        }

        /* Adaptation mobile */
        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 80%;
                max-width: 300px;
                z-index: 1050;
                height: 100vh;
                overflow-y: auto;
                padding-top: 20px;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                padding-top: 70px;
            }

            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none;
            }

            .overlay.show {
                display: block;
            }
        }

        /* Style pour les cartes de tâches */
        .kanban-card {
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid;
            transition: all 0.3s;
        }

        .kanban-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .todo-card {
            border-left-color: #0dcaf0;
        }

        .inprogress-card {
            border-left-color: #ffc107;
        }

        .done-card {
            border-left-color: #198754;
        }
    </style>
</head>
<body>
    <!-- Header Mobile -->
    <div class="mobile-header">
        <div class="logo-container">
            <div class="logo-img"></div>
            <h3 class="logo-text">Assistant IA</h3>
        </div>
        <button class="hamburger-btn" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Overlay pour fermer le menu -->
    <div class="overlay" id="overlay"></div>

    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <div class="col-md-3 bg-primary text-white p-4 sidebar" id="sidebar">
                <div class="text-center mb-5 d-md-block d-none">
                    <div id="avatar-animation" class="avatar-animation mb-3"></div>
                    <h2>Assistant IA</h2>
                    <p class="text-white-50">Votre compagnon intelligent</p>
                </div>
                
                <!-- Header intégré dans le menu mobile -->
                <div class="d-md-none mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h4"><i class="fas fa-tasks me-2"></i>Gestion des Tâches</h1>
                        <div>
                            <button class="btn btn-sm btn-light me-2" data-bs-toggle="modal" data-bs-target="#newTaskModal">
                                <i class="fas fa-plus me-1"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-light" id="voice-task-btn-mobile">
                                <i class="fas fa-microphone"></i>
                            </button>
                        </div>
                    </div>
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
                <header class="d-flex justify-content-between align-items-center mb-5 d-none d-md-flex">
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
                                <div class="card kanban-card todo-card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Tâche exemple</h5>
                                        <p class="card-text">Description de la tâche</p>
                                        <span class="badge bg-secondary">Normal</span>
                                    </div>
                                </div>
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
                                <h3 class="text-info" id="todo-count">1</h3>
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

    <!-- Modal Nouvelle Tâche (exemple) -->
    <div class="modal fade" id="newTaskModal" tabindex="-1" aria-labelledby="newTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newTaskModalLabel">Nouvelle Tâche</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="taskTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="taskDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="taskPriority" class="form-label">Priorité</label>
                            <select class="form-select" id="taskPriority">
                                <option value="low">Basse</option>
                                <option value="medium" selected>Normale</option>
                                <option value="high">Haute</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary">Créer Tâche</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS et dépendances -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion du menu mobile
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
            
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
            
            // Pour fermer le menu quand on clique sur un lien (sur mobile)
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                });
            });
            
            // Synchronisation des boutons vocaux
            const voiceBtn = document.getElementById('voice-task-btn');
            const voiceBtnMobile = document.getElementById('voice-task-btn-mobile');
            
            if (voiceBtn && voiceBtnMobile) {
                voiceBtnMobile.addEventListener('click', function() {
                    // Simuler le clic sur le bouton principal
                    voiceBtn.click();
                });
            }
        });
    </script>
</body>
</html>