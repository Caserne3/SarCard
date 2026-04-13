document.addEventListener('DOMContentLoaded', function () {

    // Vérification mots de passe en temps réel (register.php)

    var password = document.getElementById('password');
    var confirmPassword = document.getElementById('confirm_password');
    var passwordMessage = document.getElementById('password-match-message');
    var submitBtn = document.getElementById('register-btn');

    // On vérifie que les éléments existent (= on est sur register.php)
    if (password && confirmPassword && passwordMessage && submitBtn) {

        // Fonction qui compare les deux champs
        function verifierMotsDePasse() {
            var mdp = password.value;
            var confirmation = confirmPassword.value;

            // Si les deux champs sont vides, on cache le message
            if (mdp === '' && confirmation === '') {
                passwordMessage.textContent = '';
                submitBtn.disabled = false;
                return;
            }

            // Si un seul champ est rempli, on attend
            if (mdp === '' || confirmation === '') {
                passwordMessage.textContent = '';
                submitBtn.disabled = true;
                return;
            }

            // Comparaison
            if (mdp === confirmation) {
                passwordMessage.textContent = 'Mots de passe identiques';
                passwordMessage.style.color = '#00f0ff';  // Cyan néon
                submitBtn.disabled = false;
            } else {
                passwordMessage.textContent = 'Les mots de passe ne correspondent pas';
                passwordMessage.style.color = '#ff2d95';  // Rose néon
                submitBtn.disabled = true;
            }
        }

        // Écouter la saisie sur les deux champs
        password.addEventListener('input', verifierMotsDePasse);
        confirmPassword.addEventListener('input', verifierMotsDePasse);
    }


    // ============================================================
    // TÂCHE 2 : Filtre par rareté sur le marché (market.php)
    // ============================================================

    var filtre = document.getElementById('rarity-filter');
    var cartes = document.querySelectorAll('.card-item');

    // On vérifie que le filtre existe (= on est sur market.php)
    if (filtre && cartes.length > 0) {

        filtre.addEventListener('change', function () {
            var valeur = filtre.value;

            // Boucle sur chaque carte
            for (var i = 0; i < cartes.length; i++) {
                var carte = cartes[i];
                var rarete = carte.getAttribute('data-rarity');

                // Si "toutes" est sélectionné OU si la rareté correspond → afficher
                if (valeur === 'toutes' || rarete === valeur) {
                    carte.style.display = '';
                } else {
                    carte.style.display = 'none';
                }
            }
        });
    }

});
