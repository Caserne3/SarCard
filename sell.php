<?php
include 'includes/db_connect.php';
include 'includes/header.php';

// --- Verif connection user ---
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// --- ID de la carte BDD) ---
$inventaire_id = isset($_GET['id']) ? $_GET['id'] : 0;

// --- Verif appartenance de la carte ---
$stmt = $pdo->prepare("SELECT user_cards.id AS inventaire_id, cards.name, cards.image_url, cards.rarity
                        FROM user_cards
                        INNER JOIN cards ON user_cards.card_id = cards.id
                        WHERE user_cards.id = :inventaire_id AND user_cards.user_id = :user_id");
$stmt->execute([
    'inventaire_id' => $inventaire_id,
    'user_id'       => $_SESSION['user_id']
]);
$carte = $stmt->fetch(PDO::FETCH_ASSOC);

// blocage si fraude
if (!$carte) {
    echo "<p style='text-align: center; margin-top: 50px; color: red;'>Cette carte n'existe pas ou ne vous appartient pas.</p>";
    include 'includes/footer.php';
    exit;
}


$error = '';

// --- Formulaire mise en vente ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sale_price = isset($_POST['sale_price']) ? intval($_POST['sale_price']) : 0;

    if ($sale_price < 1) {
        $error = "Le prix doit être d'au moins 1 crédit.";
    } else {
        $stmt = $pdo->prepare("UPDATE user_cards SET is_for_sale = 1, sale_price = :price WHERE id = :id");
        $stmt->execute([
            'price' => $sale_price,
            'id'    => $inventaire_id
        ]);

        // Succes
        header("Location: market.php?success=1");
        exit;
    }
}
?>

<!-- ==================== FORMULAIRE DE MISE EN VENTE ==================== -->

<section style="max-width: 400px; margin: 40px auto; padding: 25px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center;">

    <h2>Vendre une carte</h2>

    <!-- Aperçu de la carte -->
    <img src="<?php echo htmlspecialchars($carte['image_url']); ?>"
        alt="<?php echo htmlspecialchars($carte['name']); ?>"
        style="width: 100%; max-width: 200px; border-radius: 8px; margin: 15px 0;">

    <h3><?php echo htmlspecialchars($carte['name']); ?></h3>
    <p style="color: #666; margin-bottom: 20px;">Rareté : <?php echo htmlspecialchars($carte['rarity']); ?></p>

    <!-- Message d'erreur -->
    <?php if ($error): ?>
        <p style="color: red; margin-bottom: 15px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Formulaire pour définir le prix -->
    <form method="POST" action="">
        <div style="margin-bottom: 20px;">
            <label for="sale_price">Prix de vente (en crédits) :</label><br>
            <input type="number" name="sale_price" id="sale_price" min="1" required
                style="width: 100%; padding: 8px; margin-top: 5px; text-align: center; font-size: 1.1rem;">
        </div>

        <button type="submit" class="btn" style="width: 100%;">Mettre en vente</button>
    </form>

    <p style="margin-top: 15px;">
        <a href="collection.php" style="color: var(--secondary-color);">Retour à ma collection</a>
    </p>
</section>

<?php include 'includes/footer.php'; ?>