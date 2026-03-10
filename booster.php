<?php
// --- Connexion BDD et Header ---
include 'includes/db_connect.php';
include 'includes/header.php';

// --- Vérification : l'utilisateur doit être connecté ---
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// --- Variables pour stocker les résultats ---
$error = '';
$carte_gagnee = null; // Contiendra les infos de la carte gagnée

// --- Traitement du formulaire quand on clique sur "Ouvrir un Booster" ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['open_booster'])) {

    // Requête 1 : Récupérer les crédits actuels du joueur
    $stmt = $pdo->prepare("SELECT credits FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Condition : le joueur a-t-il assez de crédits ?
    if ($user['credits'] >= 50) {

        // Requête 2 (Tirage) : Sélectionner une carte au hasard
        $stmt = $pdo->query("SELECT * FROM cards ORDER BY RAND() LIMIT 1");
        $carte_gagnee = $stmt->fetch(PDO::FETCH_ASSOC);

        // Requête 3 (Paiement) : Retirer 50 crédits au joueur
        $stmt = $pdo->prepare("UPDATE users SET credits = credits - 50 WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);

        // Requête 4 (Inventaire) : Ajouter la carte dans la collection du joueur
        $stmt = $pdo->prepare("INSERT INTO user_cards (user_id, card_id, is_for_sale) VALUES (:user_id, :card_id, 0)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'card_id' => $carte_gagnee['id']
        ]);
    } else {
        $error = "Fonds insuffisants ! Il vous faut au moins 50 crédits.";
    }
}

// --- Récupérer le solde actuel (après un éventuel achat) pour l'affichage ---
$stmt = $pdo->prepare("SELECT credits FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$solde = $stmt->fetch(PDO::FETCH_ASSOC)['credits'];
?>

<!-- ==================== INTERFACE HTML ==================== -->

<section class="booster-section" style="max-width: 500px; margin: 40px auto; text-align: center; padding: 20px;">

    <h1>Ouvrir un Booster</h1>
    <p style="font-size: 1.2rem; margin: 15px 0;">
        Votre solde : <strong style="color: var(--secondary-color);"><?php echo number_format($solde, 0); ?> Crédits</strong>
    </p>

    <!-- Message d'erreur si pas assez de crédits -->
    <?php if ($error): ?>
        <p style="color: red; margin-bottom: 15px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Formulaire avec un seul bouton pour ouvrir le booster -->
    <form method="POST" action="">
        <button type="submit" name="open_booster" class="btn" style="padding: 15px 40px; font-size: 1.2rem;">
            🎴 Ouvrir un Booster (50 Crédits)
        </button>
    </form>
</section>

<!-- ==================== RÉSULTAT : CARTE GAGNÉE ==================== -->

<?php if ($carte_gagnee): ?>
    <section style="max-width: 350px; margin: 0 auto 40px; text-align: center; padding: 25px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <h2 style="color: var(--secondary-color); margin-bottom: 15px;">🎉 Félicitations !</h2>
        <p style="margin-bottom: 15px;">Vous avez obtenu :</p>

        <!-- Image de la carte -->
        <img src="<?php echo htmlspecialchars($carte_gagnee['image_url']); ?>"
            alt="<?php echo htmlspecialchars($carte_gagnee['name']); ?>"
            style="width: 100%; max-width: 250px; border-radius: 8px; margin-bottom: 15px;">

        <!-- Nom de la carte -->
        <h3 style="font-size: 1.3rem; margin-bottom: 5px;">
            <?php echo htmlspecialchars($carte_gagnee['name']); ?>
        </h3>

        <!-- Rareté de la carte -->
        <p style="color: #666; font-size: 1rem;">
            Rareté : <strong><?php echo htmlspecialchars($carte_gagnee['rarity']); ?></strong>
        </p>

        <!-- Lien pour voir sa collection -->
        <a href="collection.php" class="btn" style="display: inline-block; margin-top: 15px;">Voir ma collection</a>
    </section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>