document.addEventListener('DOMContentLoaded', function() {
    // Initialisation
    loadSettings();
    initEventListeners();
});

function initEventListeners() {
    // Changement d'avatar
    document.getElementById('change-avatar').addEventListener('click', function() {
        document.getElementById('avatar-upload').click();
    });

    document.getElementById('avatar-upload').addEventListener('change', function(e) {
        handleAvatarUpload(e.target.files[0]);
    });

    // Sauvegarde des formulaires
    document.getElementById('profile-form').addEventListener('submit', saveProfileSettings);
    document.getElementById('preferences-form').addEventListener('submit', savePreferences);
    document.getElementById('voice-settings-form').addEventListener('submit', saveVoiceSettings);

    // Boutons actions
    document.getElementById('sync-now-btn').addEventListener('click', syncNow);
    document.getElementById('reset-data-btn').addEventListener('click', confirmResetData);
};

// Charger les paramètres
function loadSettings() {
    const settings = JSON.parse(localStorage.getItem('assistantSettings')) || getDefaultSettings();
    
    // Profil
    document.getElementById('profile-name').value = settings.profile.name;
    document.getElementById('profile-email').value = settings.profile.email;
    document.getElementById('profile-avatar').src = settings.profile.avatar;

    // Préférences
    document.getElementById('language-pref').value = settings.preferences.language;
    document.getElementById('theme-pref').value = settings.preferences.theme;
    document.getElementById('notifications-pref').checked = settings.preferences.notifications;
    document.getElementById('voice-feedback-pref').checked = settings.preferences.voiceFeedback;

    // Reconnaissance vocale
    document.getElementById('voice-language').value = settings.voice.language;
    document.getElementById('auto-start-voice').checked = settings.voice.autoStart;

    // Synchronisation
    document.getElementById('sync-frequency').value = settings.sync.frequency;
    
    // Appliquer le thème
    applyTheme(settings.preferences.theme);
}

function getDefaultSettings() {
    return {
        profile: {
            name: "Utilisateur",
            email: "user@example.com",
            avatar: "https://ui-avatars.com/api/?name=U+U&size=120&background=4e73df&color=fff"
        },
        preferences: {
            language: "fr",
            theme: "light",
            notifications: true,
            voiceFeedback: true
        },
        voice: {
            language: "fr-FR",
            autoStart: true
        },
        sync: {
            frequency: "30"
        }
    };
}

// Gestion de l'avatar
function handleAvatarUpload(file) {
    if (!file) return;
    
    if (!file.type.match('image.*')) {
        showAlert('Veuillez sélectionner une image valide', 'danger');
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('profile-avatar').src = e.target.result;
        saveToLocalStorage('profile.avatar', e.target.result);
        showAlert('Avatar mis à jour avec succès', 'success');
    };
    reader.readAsDataURL(file);
}

// Sauvegarde des paramètres
function saveProfileSettings(e) {
    e.preventDefault();
    
    const profile = {
        name: document.getElementById('profile-name').value,
        email: document.getElementById('profile-email').value,
        avatar: document.getElementById('profile-avatar').src
    };
    
    saveToLocalStorage('profile', profile);
    showAlert('Profil enregistré avec succès', 'success');
}

function savePreferences(e) {
    e.preventDefault();
    
    const preferences = {
        language: document.getElementById('language-pref').value,
        theme: document.getElementById('theme-pref').value,
        notifications: document.getElementById('notifications-pref').checked,
        voiceFeedback: document.getElementById('voice-feedback-pref').checked
    };
    
    saveToLocalStorage('preferences', preferences);
    applyTheme(preferences.theme);
    showAlert('Préférences enregistrées avec succès', 'success');
}

function saveVoiceSettings(e) {
    e.preventDefault();
    
    const voice = {
        language: document.getElementById('voice-language').value,
        autoStart: document.getElementById('auto-start-voice').checked
    };
    
    saveToLocalStorage('voice', voice);
    showAlert('Paramètres vocaux enregistrés', 'success');
}

// Application du thème
function applyTheme(theme) {
    const body = document.body;
    body.classList.remove('light-theme', 'dark-theme');
    
    if (theme === 'system') {
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    
    body.classList.add(`${theme}-theme`);
    saveToLocalStorage('preferences.theme', theme);
}

// Synchronisation
function syncNow() {
    showAlert('Synchronisation en cours...', 'info');
    // Simuler une synchronisation
    setTimeout(() => {
        showAlert('Synchronisation terminée', 'success');
    }, 2000);
}

function confirmResetData() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser toutes les données ? Cette action est irréversible.')) {
        localStorage.clear();
        loadSettings();
        showAlert('Données réinitialisées', 'info');
    }
}

// Helper functions
function saveToLocalStorage(key, value) {
    const settings = JSON.parse(localStorage.getItem('assistantSettings')) || {};
    const keys = key.split('.');
    let temp = settings;
    
    for (let i = 0; i < keys.length - 1; i++) {
        if (!temp[keys[i]]) temp[keys[i]] = {};
        temp = temp[keys[i]];
    }
    
    temp[keys[keys.length - 1]] = value;
    localStorage.setItem('assistantSettings', JSON.stringify(settings));
}

function showAlert(message, type = 'info') {
    const toast = new bootstrap.Toast(document.getElementById('notification-toast'));
    const toastBody = document.querySelector('#notification-toast .toast-body');
    
    document.getElementById('notification-toast').className = `toast show bg-${type} text-white`;
    toastBody.textContent = message;
    toast.show();
}