document.addEventListener('DOMContentLoaded', function() {
    // Initialisation
    initTaskManager();
});

function initTaskManager() {
    // Charger les tâches
    loadTasks();

    // Initialiser le drag and drop
    initSortable();

    // Gestion du formulaire
    document.getElementById('save-task').addEventListener('click', saveNewTask);

    // Commande vocale
    document.getElementById('voice-task-btn').addEventListener('click', startVoiceCommand);

    // Écouter les changements de date pour le filtre
    document.getElementById('filter-date').addEventListener('change', filterTasks);
}

function initSortable() {
    // Colonne À Faire
    new Sortable(document.getElementById('todo-tasks'), {
        group: 'tasks',
        animation: 150,
        onEnd: function(evt) {
            updateTaskStatus(evt.item.dataset.id, 'todo');
        }
    });

    // Colonne En Cours
    new Sortable(document.getElementById('inprogress-tasks'), {
        group: 'tasks',
        animation: 150,
        onEnd: function(evt) {
            updateTaskStatus(evt.item.dataset.id, 'inprogress');
        }
    });

    // Colonne Terminé
    new Sortable(document.getElementById('done-tasks'), {
        group: 'tasks',
        animation: 150,
        onEnd: function(evt) {
            updateTaskStatus(evt.item.dataset.id, 'done');
        }
    });
}

// Charger les tâches depuis l'API
async function loadTasks() {
    try {
        const response = await fetch('../api/tasks.php');
        const tasks = await response.json();
        displayTasks(tasks);
        updateTaskCounts(tasks);
    } catch (error) {
        console.error('Erreur:', error);
        showAlert('Erreur lors du chargement des tâches', 'danger');
    }
}

// Afficher les tâches dans les colonnes
function displayTasks(tasks) {
    const todoContainer = document.getElementById('todo-tasks');
    const inprogressContainer = document.getElementById('inprogress-tasks');
    const doneContainer = document.getElementById('done-tasks');

    // Vider les conteneurs
    todoContainer.innerHTML = '';
    inprogressContainer.innerHTML = '';
    doneContainer.innerHTML = '';

    // Trier les tâches par date d'échéance
    tasks.sort((a, b) => new Date(a.due_date) - new Date(b.due_date));

    // Ajouter chaque tâche à la colonne appropriée
    tasks.forEach(task => {
        const taskElement = createTaskElement(task);
        
        switch(task.status) {
            case 'inprogress':
                inprogressContainer.appendChild(taskElement);
                break;
            case 'done':
                doneContainer.appendChild(taskElement);
                break;
            default:
                todoContainer.appendChild(taskElement);
        }
    });
}

// Créer un élément de tâche
function createTaskElement(task) {
    const element = document.createElement('div');
    element.className = `task-card card mb-2 ${getPriorityClass(task.priority)}`;
    element.dataset.id = task.id;

    // Formater la date
    const dueDate = task.due_date ? formatDate(task.due_date) : 'Pas de date';

    element.innerHTML = `
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="card-title mb-1">${task.title}</h6>
                    ${task.description ? `<p class="card-text small text-muted mb-2">${task.description}</p>` : ''}
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item edit-task" href="#" data-id="${task.id}">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a></li>
                        <li><a class="dropdown-item delete-task" href="#" data-id="${task.id}">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </a></li>
                    </ul>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="badge bg-secondary">${task.category || 'Sans catégorie'}</span>
                <small class="${isTaskOverdue(task.due_date) ? 'text-danger' : 'text-muted'}">
                    <i class="fas fa-calendar-day me-1"></i>${dueDate}
                </small>
            </div>
        </div>
    `;

    // Ajouter les événements
    element.querySelector('.edit-task').addEventListener('click', (e) => {
        e.preventDefault();
        editTask(task.id);
    });

    element.querySelector('.delete-task').addEventListener('click', (e) => {
        e.preventDefault();
        deleteTask(task.id);
    });

    return element;
}

// Mettre à jour les compteurs
function updateTaskCounts(tasks) {
    const todoCount = tasks.filter(t => t.status === 'todo').length;
    const inprogressCount = tasks.filter(t => t.status === 'inprogress').length;
    const doneCount = tasks.filter(t => t.status === 'done').length;

    document.getElementById('todo-count').textContent = todoCount;
    document.getElementById('inprogress-count').textContent = inprogressCount;
    document.getElementById('done-count').textContent = doneCount;
}

// Sauvegarder une nouvelle tâche
async function saveNewTask() {
    const title = document.getElementById('task-title').value;
    const description = document.getElementById('task-description').value;
    const dueDate = document.getElementById('task-due-date').value;
    const priority = document.getElementById('task-priority').value;
    const category = document.getElementById('task-category').value;

    if (!title) {
        showAlert('Le titre est obligatoire', 'warning');
        return;
    }

    try {
        const response = await fetch('../api/tasks.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                title,
                description,
                due_date: dueDate,
                priority,
                category,
                status: 'todo'
            })
        });

        const data = await response.json();
        if (data.status === 'success') {
            // Fermer le modal et recharger les tâches
            bootstrap.Modal.getInstance(document.getElementById('newTaskModal')).hide();
            loadTasks();
            showAlert('Tâche créée avec succès', 'success');
            document.getElementById('task-form').reset();
        }
    } catch (error) {
        console.error('Erreur:', error);
        showAlert('Erreur lors de la création de la tâche', 'danger');
    }
}

// Mettre à jour le statut d'une tâche
async function updateTaskStatus(taskId, newStatus) {
    try {
        const response = await fetch(`../api/tasks.php?id=${taskId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });

        const data = await response.json();
        if (data.status === 'success') {
            loadTasks();
        }
    } catch (error) {
        console.error('Erreur:', error);
        showAlert('Erreur lors de la mise à jour de la tâche', 'danger');
    }
}

// Modifier une tâche
async function editTask(taskId) {
    try {
        // Charger les détails de la tâche
        const response = await fetch(`../api/tasks.php?id=${taskId}`);
        const task = await response.json();

        // Remplir le formulaire
        document.getElementById('task-title').value = task.title;
        document.getElementById('task-description').value = task.description || '';
        document.getElementById('task-due-date').value = task.due_date || '';
        document.getElementById('task-priority').value = task.priority;
        document.getElementById('task-category').value = task.category || '';

        // Changer le bouton pour mettre à jour
        const saveBtn = document.getElementById('save-task');
        saveBtn.textContent = 'Mettre à jour';
        saveBtn.onclick = function() {
            updateTask(taskId);
        };

        // Ouvrir le modal
        const modal = new bootstrap.Modal(document.getElementById('newTaskModal'));
        modal.show();
    } catch (error) {
        console.error('Erreur:', error);
        showAlert('Erreur lors du chargement de la tâche', 'danger');
    }
}

// Mettre à jour une tâche existante
async function updateTask(taskId) {
    const title = document.getElementById('task-title').value;
    const description = document.getElementById('task-description').value;
    const dueDate = document.getElementById('task-due-date').value;
    const priority = document.getElementById('task-priority').value;
    const category = document.getElementById('task-category').value;

    try {
        const response = await fetch(`../api/tasks.php?id=${taskId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                title,
                description,
                due_date: dueDate,
                priority,
                category
            })
        });

        const data = await response.json();
        if (data.status === 'success') {
            // Fermer le modal et recharger les tâches
            bootstrap.Modal.getInstance(document.getElementById('newTaskModal')).hide();
            loadTasks();
            showAlert('Tâche mise à jour avec succès', 'success');
            
            // Réinitialiser le bouton
            const saveBtn = document.getElementById('save-task');
            saveBtn.textContent = 'Enregistrer';
            saveBtn.onclick = saveNewTask;
        }
    } catch (error) {
        console.error('Erreur:', error);
        showAlert('Erreur lors de la mise à jour de la tâche', 'danger');
    }
}

// Supprimer une tâche
async function deleteTask(taskId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) return;

    try {
        const response = await fetch(`../api/tasks.php?id=${taskId}`, {
            method: 'DELETE'
        });

        const data = await response.json();
        if (data.status === 'success') {
            loadTasks();
            showAlert('Tâche supprimée avec succès', 'success');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showAlert('Erreur lors de la suppression de la tâche', 'danger');
    }
}

// Commandes vocales
function startVoiceCommand() {
    if (!('webkitSpeechRecognition' in window)) {
        showAlert('La reconnaissance vocale n\'est pas supportée par votre navigateur', 'warning');
        return;
    }

    const recognition = new webkitSpeechRecognition();
    recognition.lang = 'fr-FR';
    recognition.interimResults = false;

    showAlert('Parlez maintenant pour créer une tâche...', 'info');

    recognition.onresult = function(event) {
        const transcript = event.results[0][0].transcript;
        processVoiceCommand(transcript);
    };

    recognition.onerror = function(event) {
        showAlert('Erreur de reconnaissance: ' + event.error, 'danger');
    };

    recognition.start();
}

function processVoiceCommand(command) {
    // Exemple: "Ajouter une tâche Faire les courses demain haute priorité"
    const regex = /ajouter une tâche (.+?) (demain|aujourd'hui|le \d{2}\/\d{2}\/\d{4})? ?(haute|moyenne|basse)? priorité?/i;
    const match = command.match(regex);

    if (match) {
        const title = match[1];
        let dueDate = '';
        let priority = 'medium';

        // Gérer la date
        if (match[2]) {
            if (match[2].toLowerCase() === 'demain') {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                dueDate = tomorrow.toISOString().split('T')[0];
            } else if (match[2].toLowerCase() === 'aujourd\'hui') {
                dueDate = new Date().toISOString().split('T')[0];
            } else {
                // Convertir "le 12/05/2023" en format ISO
                const parts = match[2].split('/');
                dueDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
        }

        // Gérer la priorité
        if (match[3]) {
            priority = match[3].toLowerCase();
        }

        // Remplir le formulaire
        document.getElementById('task-title').value = title;
        if (dueDate) document.getElementById('task-due-date').value = dueDate;
        document.getElementById('task-priority').value = priority;

        // Ouvrir le modal
        const modal = new bootstrap.Modal(document.getElementById('newTaskModal'));
        modal.show();

        showAlert('Tâche créée par commande vocale', 'success');
    } else {
        showAlert('Je n\'ai pas compris la commande. Essayez: "Ajouter une tâche [description] [date] [priorité]"', 'warning');
    }
}

// Helper functions
function getPriorityClass(priority) {
    switch(priority) {
        case 'high': return 'border-start border-danger border-3';
        case 'medium': return 'border-start border-warning border-3';
        case 'low': return 'border-start border-info border-3';
        default: return '';
    }
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR');
}

function isTaskOverdue(dueDate) {
    if (!dueDate) return false;
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return new Date(dueDate) < today;
}

function filterTasks() {
    const filterDate = document.getElementById('filter-date').value;
    // Implémentez la logique de filtrage selon vos besoins
}

function showAlert(message, type = 'info') {
    const toast = new bootstrap.Toast(document.getElementById('notification-toast'));
    const toastBody = document.querySelector('#notification-toast .toast-body');
    
    document.getElementById('notification-toast').className = `toast show bg-${type} text-white`;
    toastBody.textContent = message;
    toast.show();
}