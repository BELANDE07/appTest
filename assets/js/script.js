// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Charger les animations Lottie
    const avatarAnim = lottie.loadAnimation({
        container: document.getElementById('avatar-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: 'assets/animations/avatar.json'
    });

    const voiceAnim = lottie.loadAnimation({
        container: document.getElementById('voice-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: false,
        path: 'assets/animations/voice.json'
    });

    // Initialiser la reconnaissance vocale
    initVoiceRecognition();
    
    // Charger les rappels
    loadReminders();
    
    // Gestion du formulaire
    document.getElementById('reminder-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const time = document.getElementById('reminder-time').value;
        const date = document.getElementById('reminder-date').value;
        const message = document.getElementById('reminder-text').value;
        
        if (time && message) {
            const fullTime = date ? `${date} ${time}` : time;
            setReminder(fullTime, message);
            this.reset();
            showNotification('Rappel enregistré avec succès', 'success');
        }
    });
});

// Reconnaissance vocale
let recognition;
let isListening = false;

function initVoiceRecognition() {
    window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    
    if (!window.SpeechRecognition) {
        showNotification("La reconnaissance vocale n'est pas supportée sur votre navigateur", 'danger');
        return;
    }
    
    recognition = new SpeechRecognition();
    recognition.interimResults = true;
    recognition.lang = 'fr-FR';
    recognition.continuous = true;
    
    recognition.onstart = function() {
        isListening = true;
        document.getElementById('voice-command-btn').classList.add('active');
        document.getElementById('voice-status').className = 'badge bg-danger';
        document.getElementById('voice-status').innerHTML = '<i class="fas fa-microphone me-1"></i> En écoute...';
        voiceAnim.play();
    };
    
    recognition.onend = function() {
        isListening = false;
        document.getElementById('voice-command-btn').classList.remove('active');
        document.getElementById('voice-status').className = 'badge bg-success';
        document.getElementById('voice-status').innerHTML = '<i class="fas fa-microphone me-1"></i> Prêt';
        voiceAnim.stop();
    };
    
    recognition.onresult = function(event) {
        const transcript = Array.from(event.results)
            .map(result => result[0])
            .map(result => result.transcript)
            .join('');
        
        const voiceFeedback = document.getElementById('voice-feedback');
        voiceFeedback.textContent = transcript;
        voiceFeedback.classList.remove('d-none');
        
        if (event.results[0].isFinal) {
            processVoiceCommand(transcript);
            setTimeout(() => voiceFeedback.classList.add('d-none'), 3000);
        }
    };
    
    recognition.onerror = function(event) {
        console.error('Erreur de reconnaissance:', event.error);
        showNotification(`Erreur vocale: ${event.error}`, 'danger');
    };
    
    // Bouton de commande vocale
    document.getElementById('voice-command-btn').addEventListener('click', function() {
        if (!isListening) {
            recognition.start();
            showNotification('En écoute... parlez maintenant', 'info');
        } else {
            recognition.stop();
        }
    });
}

// Traitement des commandes vocales
function processVoiceCommand(command) {
    console.log("Commande reçue:", command);
    speak("J'ai reçu votre commande: " + command);
    
    // Commandes de rappel
    if (command.toLowerCase().includes('rappelle-moi') || command.toLowerCase().includes('rappeler')) {
        handleReminderCommand(command);
    }
    // Commandes de temps
    else if (command.toLowerCase().includes('quelle heure est-il')) {
        const now = new Date();
        speak(`Il est ${now.getHours()} heures et ${now.getMinutes()} minutes`);
    }
    // Autres commandes
    else {
        speak("Je n'ai pas compris la commande. Pouvez-vous répéter ?");
    }
}

// Gestion des commandes de rappel
function handleReminderCommand(command) {
    const timeMatch = command.match(/(\d{1,2})[h\s](\d{0,2})?/);
    const dateMatch = command.match(/(aujourd'hui|demain|\d{1,2}\/\d{1,2}\/\d{4})/i);
    
    if (timeMatch) {
        let hours = timeMatch[1].padStart(2, '0');
        let minutes = timeMatch[2] ? timeMatch[2].padStart(2, '0') : '00';
        const time = `${hours}:${minutes}`;
        
        let date = '';
        if (dateMatch) {
            if (dateMatch[1].toLowerCase() === 'demain') {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                date = tomorrow.toISOString().split('T')[0];
            } else if (dateMatch[1].toLowerCase() !== 'aujourd\'hui') {
                date = dateMatch[1];
            }
        }
        
        const message = command.replace(/(rappelle-moi|rappeler|à|le|demain|aujourd'hui|\d{1,2}[h\s]\d{0,2}|\d{1,2}\/\d{1,2}\/\d{4})/gi, '').trim();
        
        if (message) {
            const fullTime = date ? `${date} ${time}` : time;
            setReminder(fullTime, message);
            speak(`Rappel programmé pour ${time}${date ? ' le ' + date : ''} : ${message}`);
        } else {
            speak("Je n'ai pas compris le message du rappel. Pouvez-vous répéter ?");
        }
    } else {
        speak("Je n'ai pas compris l'heure du rappel. Pouvez-vous préciser ?");
    }
}

// Synthèse vocale
function speak(text) {
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'fr-FR';
    utterance.rate = 1.0;
    utterance.pitch = 1.0;
    
    // Choisir une voix féminine si disponible
    const voices = window.speechSynthesis.getVoices();
    const frenchVoice = voices.find(voice => voice.lang === 'fr-FR' && voice.name.includes('Female'));
    if (frenchVoice) {
        utterance.voice = frenchVoice;
    }
    
    speechSynthesis.speak(utterance);
    showNotification(`Assistant: ${text}`, 'info');
}

// Gestion des rappels
async function setReminder(time, message) {
    try {
        const response = await fetch('api/reminders.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ time, message })
        });
        
        const data = await response.json();
        if (data.status === 'success') {
            loadReminders();
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur lors de l\'enregistrement du rappel', 'danger');
    }
}

async function loadReminders() {
    try {
        const response = await fetch('api/reminders.php');
        const reminders = await response.json();
        
        const container = document.getElementById('reminders-container');
        const countElement = document.getElementById('reminders-count');
        container.innerHTML = '';
        countElement.textContent = reminders.length;
        
        if (reminders.length === 0) {
            container.innerHTML = '<li class="list-group-item text-muted">Aucun rappel programmé</li>';
            return;
        }
        
        reminders.forEach(reminder => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.innerHTML = `
                <div>
                    <strong>${reminder.time}</strong>
                    <p class="mb-0">${reminder.message}</p>
                </div>
                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${reminder.id}">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(li);
            
            // Supprimer un rappel
            li.querySelector('.delete-btn').addEventListener('click', function() {
                deleteReminder(reminder.id);
            });
        });
        
        // Vérifier les rappels à venir
        checkUpcomingReminders(reminders);
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur lors du chargement des rappels', 'danger');
    }
}

async function deleteReminder(id) {
    try {
        const response = await fetch(`api/reminders.php?id=${id}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        if (data.status === 'success') {
            loadReminders();
            showNotification('Rappel supprimé', 'success');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur lors de la suppression', 'danger');
    }
}

function checkUpcomingReminders(reminders) {
    const now = new Date();
    const currentTime = now.getHours() * 60 + now.getMinutes();
    
    reminders.forEach(reminder => {
        const [hours, minutes] = reminder.time.split(':');
        const reminderTime = parseInt(hours) * 60 + parseInt(minutes);
        
        // Si le rappel est dans les 5 prochaines minutes
        if (reminderTime >= currentTime && reminderTime <= currentTime + 5) {
            const timeLeft = reminderTime - currentTime;
            setTimeout(() => {
                speak(`Rappel: ${reminder.message}`);
                showNotification(`Rappel: ${reminder.message}`, 'warning', 10000);
            }, timeLeft * 60000);
        }
    });
}

// Notifications
function showNotification(message, type = 'info', delay = 3000) {
    const toastEl = document.getElementById('notification-toast');
    const toastBody = toastEl.querySelector('.toast-body');
    
    toastEl.className = `toast show bg-${type} text-white`;
    toastBody.textContent = message;
    
    const toast = new bootstrap.Toast(toastEl, { delay: delay });
    toast.show();
}