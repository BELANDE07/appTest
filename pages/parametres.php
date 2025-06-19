<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Assistant Personnel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <div class="col-md-3 bg-primary text-white p-4 sidebar">
                <!-- Même sidebar que index.html -->
                <!-- ... -->
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 p-4 main-content">
                <header class="mb-5">
                    <h1 class="display-6"><i class="fas fa-cog me-2"></i>Paramètres</h1>
                    <p class="text-muted">Personnalisez votre assistant</p>
                </header>

                <div class="row">
                    <!-- Profil -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profil</h5>
                            </div>
                            <div class="card-body">
                                <form id="profile-form">
                                    <div class="mb-3 text-center">
                                        <img src="https://ui-avatars.com/api/?name=U+U&size=120&background=4e73df&color=fff" 
                                             class="rounded-circle mb-3" id="profile-avatar">
                                        <input type="file" class="d-none" id="avatar-upload">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="change-avatar">
                                            <i class="fas fa-camera me-1"></i> Changer
                                        </button>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="profile-name" value="Utilisateur">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="profile-email" value="user@example.com">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-1"></i> Enregistrer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Préférences -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Préférences</h5>
                            </div>
                            <div class="card-body">
                                <form id="preferences-form">
                                    <div class="mb-3">
                                        <label class="form-label">Langue</label>
                                        <select class="form-select" id="language-pref">
                                            <option value="fr" selected>Français</option>
                                            <option value="en">English</option>
                                            <option value="es">Español</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Thème</label>
                                        <select class="form-select" id="theme-pref">
                                            <option value="light" selected>Clair</option>
                                            <option value="dark">Sombre</option>
                                            <option value="system">Système</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="notifications-pref" checked>
                                        <label class="form-check-label" for="notifications-pref">Notifications</label>
                                    </div>
                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="voice-feedback-pref" checked>
                                        <label class="form-check-label" for="voice-feedback-pref">Feedback vocal</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-1"></i> Enregistrer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paramètres avancés -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Paramètres Avancés</h5>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="advancedSettings">
                            <!-- Section Reconnaissance Vocale -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#voiceSettings" aria-expanded="true">
                                        <i class="fas fa-microphone me-2"></i> Reconnaissance Vocale
                                    </button>
                                </h2>
                                <div id="voiceSettings" class="accordion-collapse collapse show" 
                                     data-bs-parent="#advancedSettings">
                                    <div class="accordion-body">
                                        <form id="voice-settings-form">
                                            <div class="mb-3">
                                                <label class="form-label">Langue de reconnaissance</label>
                                                <select class="form-select" id="voice-language">
                                                    <option value="fr-FR" selected>Français (France)</option>
                                                    <option value="fr-CA">Français (Canada)</option>
                                                    <option value="en-US">English (US)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="auto-start-voice" checked>
                                                <label class="form-check-label" for="auto-start-voice">
                                                    Démarrer automatiquement l'écoute
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i> Enregistrer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section Synchronisation -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#syncSettings" aria-expanded="false">
                                        <i class="fas fa-sync-alt me-2"></i> Synchronisation
                                    </button>
                                </h2>
                                <div id="syncSettings" class="accordion-collapse collapse" 
                                     data-bs-parent="#advancedSettings">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <label class="form-label">Fréquence de synchronisation</label>
                                            <select class="form-select" id="sync-frequency">
                                                <option value="15">Toutes les 15 minutes</option>
                                                <option value="30" selected>Toutes les 30 minutes</option>
                                                <option value="60">Toutes les heures</option>
                                                <option value="manual">Manuelle</option>
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-outline-primary" id="sync-now-btn">
                                                <i class="fas fa-sync me-1"></i> Synchroniser maintenant
                                            </button>
                                            <button class="btn btn-danger" id="reset-data-btn">
                                                <i class="fas fa-trash me-1"></i> Réinitialiser les données
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/settings.js"></script>
</body>
</html>