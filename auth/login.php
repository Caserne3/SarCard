<?php
include '../includes/db_connect.php';
include '../includes/header.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe avec password_verify (compare le hash stocké en BDD)
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../index.php");
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<section class="auth-section" style="max-width: 400px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2>Connexion</h2>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Message de succès après inscription -->
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Inscription réussie ! Vous pouvez vous connecter.</p>
    <?php endif; ?>

    <form method="POST" action="">
        <div style="margin-bottom: 15px;">
            <label for="email">Email :</label><br>
            <input type="email" name="email" id="email" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="password">Mot de passe :</label><br>
            <input type="password" name="password" id="password" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <button type="submit" class="btn" style="width: 100%;">Se connecter</button>
    </form>
    <p style="margin-top: 15px; text-align: center;">Pas encore de compte ? <a href="register.php" style="color: var(--secondary-color);">Inscrivez-vous</a></p>
</section>

<?php include '../includes/footer.php'; ?>