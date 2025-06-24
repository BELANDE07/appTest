
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