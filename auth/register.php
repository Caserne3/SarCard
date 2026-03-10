<?php
// --- 1) On inclut la connexion à la BDD et le header ---
include '../includes/db_connect.php';
include '../includes/header.php';

// --- 2) Variable pour stocker les messages d'erreur ---
$error = '';

// --- 3) On traite le formulaire seulement s'il a été envoyé (méthode POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire
    $username         = $_POST['username'];
    $email            = $_POST['email'];
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // --- Vérification 1 : Tous les champs sont remplis ? ---
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Veuillez remplir tous les champs.";

        // --- Vérification 2 : Les deux mots de passe sont identiques ? ---
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // --- Vérification 3 : L'email ou le pseudo est-il déjà pris ? ---
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
        $stmt->execute(['email' => $email, 'username' => $username]);
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_user) {
            $error = "Cet email ou ce pseudo est déjà utilisé.";
        } else {
            // --- Tout est bon ! On insère le nouvel utilisateur ---

            // Sécuriser le mot de passe avec password_hash (crée un hash impossible à inverser)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insérer dans la table users avec 500 crédits de départ
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, credits) VALUES (:username, :email, :password, :credits)");
            $stmt->execute([
                'username' => $username,
                'email'    => $email,
                'password' => $hashed_password,
                'credits'  => 500
            ]);

            // Rediriger vers la page de connexion avec un message de succès
            header("Location: login.php?success=1");
            exit;
        }
    }
}
?>

<section class="auth-section" style="max-width: 400px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2>Inscription</h2>

    <!-- Afficher le message d'erreur s'il y en a un -->
    <?php if ($error): ?>
        <p style="color: red; margin-bottom: 10px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Le formulaire envoie les données en POST (vers cette même page) -->
    <form method="POST" action="">

        <!-- Champ Pseudo -->
        <div style="margin-bottom: 15px;">
            <label for="username">Pseudo :</label><br>
            <input type="text" name="username" id="username" required
                style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <!-- Champ Email -->
        <div style="margin-bottom: 15px;">
            <label for="email">Email :</label><br>
            <input type="email" name="email" id="email" required
                style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <!-- Champ Mot de passe -->
        <div style="margin-bottom: 15px;">
            <label for="password">Mot de passe :</label><br>
            <input type="password" name="password" id="password" required
                style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <!-- Champ Confirmer le mot de passe -->
        <div style="margin-bottom: 20px;">
            <label for="confirm_password">Confirmer le mot de passe :</label><br>
            <input type="password" name="confirm_password" id="confirm_password" required
                style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <!-- Bouton d'envoi -->
        <button type="submit" class="btn" style="width: 100%;">S'inscrire</button>
    </form>

    <!-- Lien vers la page de connexion -->
    <p style="margin-top: 15px; text-align: center;">
        Déjà un compte ? <a href="login.php" style="color: var(--secondary-color);">Connectez-vous</a>
    </p>
</section>

<?php include '../includes/footer.php'; ?>