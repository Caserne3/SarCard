<?php
include 'includes/header.php';

$success_message = '';
$error_message = '';

// Formulaire contact
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom    = $_POST['nom'];
    $email  = $_POST['email'];
    $sujet  = $_POST['sujet'];
    $message = $_POST['message'];

    // Verif champs remplis
    if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
        $error_message = "Veuillez remplir tous les champs.";

        // Verif format de l'email
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Le format de l'email est invalide.";
    } else {
        $success_message = "Merci pour votre message, notre équipe vous répondra sous 24h promis.";
    }
}
?>

<section style="max-width: 500px; margin: 40px auto; padding: 25px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">

    <h1>Contactez-nous</h1>
    <p style="margin-bottom: 20px; color: #666;">Une question ? Un problème ? Écrivez-nous !</p>

    <!---succès --->
    <?php if ($success_message): ?>
        <p style="color: green; background: #e8f5e9; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <?php echo $success_message; ?>
        </p>
    <?php endif; ?>

    <!-- erreur -->
    <?php if ($error_message): ?>
        <p style="color: red; background: #ffebee; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <?php echo $error_message; ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">
        <div style="margin-bottom: 15px;">
            <label for="nom">Nom :</label><br>
            <input type="text" name="nom" id="nom" required
                style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email">Email :</label><br>
            <input type="email" name="email" id="email" required
                style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="sujet">Sujet :</label><br>
            <input type="text" name="sujet" id="sujet" required
                style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="message">Message :</label><br>
            <textarea name="message" id="message" rows="5" required
                style="width: 100%; padding: 8px; margin-top: 5px; resize: vertical;"></textarea>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Envoyer</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>