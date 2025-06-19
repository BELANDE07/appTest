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
                    <div>
                        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#newReminderModal">
                            <i class="fas fa-plus me-1"></i> Nouveau Rappel
                        </button>
                        <button class="btn btn-success" id="startRecording">
                            <i class="fas fa-microphone me-1"></i> Note Vocale
                        </button>
                    </div>
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

    <!-- Modal Note Vocale -->
    <div class="modal fade" id="voiceNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un rappel par note vocale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div id="recordingStatus" class="mb-2">
                            <i class="fas fa-microphone-slash fa-3x text-muted"></i>
                            <p class="mt-2">Prêt à enregistrer</p>
                        </div>
                        <div id="recordingTimer" class="h4 mb-3 d-none">00:00</div>
                        <button id="toggleRecording" class="btn btn-danger btn-lg rounded-circle">
                            <i class="fas fa-microphone"></i>
                        </button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transcription</label>
                        <textarea class="form-control" id="voice-transcription" rows="3" readonly></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="voice-date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Heure</label>
                            <input type="time" class="form-control" id="voice-time" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="save-voice-reminder" disabled>
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fonctions pour la gestion des rappels
        document.addEventListener('DOMContentLoaded', function() {
            // Charger les rappels
            loadReminders();
            
            // Enregistrer un nouveau rappel
            document.getElementById('save-reminder').addEventListener('click', function() {
                const message = document.getElementById('reminder-message').value;
                const date = document.getElementById('reminder-date').value;
                const time = document.getElementById('reminder-time').value;
                const repeat = document.getElementById('reminder-repeat').value;
                
                if (!message || !time) {
                    alert('Veuillez remplir tous les champs obligatoires');
                    return;
                }
                
                const reminder = {
                    message: message,
                    time: `${date} ${time}`,
                    repeat: repeat
                };
                
                saveReminder(reminder);
            });
            
            // Gestion de l'enregistrement vocal
            document.getElementById('startRecording').addEventListener('click', function() {
                const voiceModal = new bootstrap.Modal(document.getElementById('voiceNoteModal'));
                voiceModal.show();
            });
            
            // Initialiser l'API Web Speech
            initSpeechRecognition();
        });
        
        function loadReminders() {
            fetch('../api/reminders.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('reminders-table');
                    tableBody.innerHTML = '';
                    
                    data.forEach(reminder => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${reminder.time}</td>
                            <td>${reminder.message}</td>
                            <td>${reminder.is_completed ? '<span class="badge bg-success">Terminé</span>' : '<span class="badge bg-warning">Actif</span>'}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editReminder('${reminder.id}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteReminder('${reminder.id}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                    
                    document.getElementById('reminders-count').textContent = data.length;
                })
                .catch(error => console.error('Erreur:', error));
        }
        
        function saveReminder(reminder) {
            fetch('../api/reminders.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(reminder)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    loadReminders();
                    bootstrap.Modal.getInstance(document.getElementById('newReminderModal')).hide();
                    document.getElementById('reminder-form').reset();
                } else {
                    alert('Erreur lors de l\'enregistrement du rappel');
                }
            })
            .catch(error => console.error('Erreur:', error));
        }
        
        function deleteReminder(id) {
            if (confirm('Voulez-vous vraiment supprimer ce rappel ?')) {
                fetch(`../api/reminders.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        loadReminders();
                    } else {
                        alert('Erreur lors de la suppression du rappel');
                    }
                })
                .catch(error => console.error('Erreur:', error));
            }
        }
        
        function initSpeechRecognition() {
            const toggleRecording = document.getElementById('toggleRecording');
            const recordingStatus = document.getElementById('recordingStatus');
            const recordingTimer = document.getElementById('recordingTimer');
            const transcription = document.getElementById('voice-transcription');
            const saveBtn = document.getElementById('save-voice-reminder');
            
            let recognition;
            let isRecording = false;
            let timerInterval;
            let seconds = 0;
            
            try {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                recognition = new SpeechRecognition();
                recognition.continuous = true;
                recognition.interimResults = true;
                recognition.lang = 'fr-FR';
                
                recognition.onresult = function(event) {
                    let interimTranscript = '';
                    let finalTranscript = '';
                    
                    for (let i = event.resultIndex; i < event.results.length; i++) {
                        const transcript = event.results[i][0].transcript;
                        if (event.results[i].isFinal) {
                            finalTranscript += transcript;
                        } else {
                            interimTranscript += transcript;
                        }
                    }
                    
                    transcription.value = finalTranscript || interimTranscript;
                    saveBtn.disabled = !finalTranscript;
                };
                
                recognition.onerror = function(event) {
                    console.error('Erreur de reconnaissance:', event.error);
                    stopRecording();
                };
                
                toggleRecording.addEventListener('click', function() {
                    if (isRecording) {
                        stopRecording();
                    } else {
                        startRecording();
                    }
                });
                
                function startRecording() {
                    recognition.start();
                    isRecording = true;
                    seconds = 0;
                    
                    // Mettre à jour l'interface
                    recordingStatus.innerHTML = '<i class="fas fa-microphone fa-3x text-danger"></i><p class="mt-2">Enregistrement en cours...</p>';
                    recordingTimer.classList.remove('d-none');
                    toggleRecording.innerHTML = '<i class="fas fa-stop"></i>';
                    
                    // Démarrer le chronomètre
                    timerInterval = setInterval(function() {
                        seconds++;
                        const minutes = Math.floor(seconds / 60);
                        const remainingSeconds = seconds % 60;
                        recordingTimer.textContent = `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
                    }, 1000);
                }
                
                function stopRecording() {
                    recognition.stop();
                    isRecording = false;
                    
                    // Mettre à jour l'interface
                    recordingStatus.innerHTML = '<i class="fas fa-microphone-slash fa-3x text-muted"></i><p class="mt-2">Enregistrement terminé</p>';
                    clearInterval(timerInterval);
                    toggleRecording.innerHTML = '<i class="fas fa-microphone"></i>';
                }
                
                // Sauvegarder le rappel vocal
                saveBtn.addEventListener('click', function() {
                    const message = transcription.value;
                    const date = document.getElementById('voice-date').value;
                    const time = document.getElementById('voice-time').value;
                    
                    if (!message || !time) {
                        alert('Veuillez remplir tous les champs obligatoires');
                        return;
                    }
                    
                    const reminder = {
                        message: message,
                        time: `${date} ${time}`
                    };
                    
                    saveReminder(reminder);
                    
                    // Réinitialiser et fermer le modal
                    transcription.value = '';
                    document.getElementById('voice-date').value = '';
                    document.getElementById('voice-time').value = '';
                    saveBtn.disabled = true;
                    recordingTimer.classList.add('d-none');
                    recordingTimer.textContent = '00:00';
                    
                    bootstrap.Modal.getInstance(document.getElementById('voiceNoteModal')).hide();
                });
                
            } catch (e) {
                console.error('La reconnaissance vocale n\'est pas supportée par ce navigateur', e);
                toggleRecording.disabled = true;
                recordingStatus.innerHTML = '<i class="fas fa-exclamation-triangle fa-3x text-warning"></i><p class="mt-2">La reconnaissance vocale n\'est pas disponible</p>';
            }
        }
    </script>
</body>
</html>