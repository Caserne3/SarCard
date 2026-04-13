<?php
include 'includes/db_connect.php';
include 'includes/header.php';

$error = '';
$achat_ok = '';

// --- Traitement de l'achat si le formulaire est soumis ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy_card_id'])) {

    // Vérifier que l'acheteur est connecté
    if (!isset($_SESSION['user_id'])) {
        $error = "Vous devez être connecté pour acheter.";
    } else {
        $card_inventory_id = intval($_POST['buy_card_id']);

        $stmt = $pdo->prepare("SELECT user_cards.id, user_cards.user_id AS seller_id, user_cards.sale_price, user_cards.card_id
                                FROM user_cards
                                WHERE user_cards.id = :id AND user_cards.is_for_sale = 1");
        $stmt->execute(['id' => $card_inventory_id]);
        $carte_en_vente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$carte_en_vente) {
            $error = "Cette carte n'est plus disponible.";
        } elseif ($carte_en_vente['seller_id'] == $_SESSION['user_id']) {
            $error = "Vous ne pouvez pas acheter votre propre carte.";
        } else {
            // Vérifier le solde de l'acheteur
            $stmt = $pdo->prepare("SELECT credits FROM users WHERE id = :id");
            $stmt->execute(['id' => $_SESSION['user_id']]);
            $acheteur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($acheteur['credits'] < $carte_en_vente['sale_price']) {
                $error = "Vous n'avez pas assez de crédits.";
            } else {
                //  Retirer les crédits à l'acheteur
                $stmt = $pdo->prepare("UPDATE users SET credits = credits - :prix WHERE id = :id");
                $stmt->execute(['prix' => $carte_en_vente['sale_price'], 'id' => $_SESSION['user_id']]);

                // Ajouter les crédits au vendeur
                $stmt = $pdo->prepare("UPDATE users SET credits = credits + :prix WHERE id = :id");
                $stmt->execute(['prix' => $carte_en_vente['sale_price'], 'id' => $carte_en_vente['seller_id']]);

                //  Transférer la carte à l'acheteur et retirer de la vente
                $stmt = $pdo->prepare("UPDATE user_cards SET user_id = :buyer_id, is_for_sale = 0, sale_price = NULL WHERE id = :id");
                $stmt->execute(['buyer_id' => $_SESSION['user_id'], 'id' => $card_inventory_id]);

                $achat_ok = "Carte achetée avec succès !";
            }
        }
    }
}

// --- Récupération des cartes en vente ---
$query = "SELECT user_cards.id AS inventaire_id, user_cards.user_id AS seller_id,
                 cards.name, cards.image_url, cards.rarity,
                 user_cards.sale_price, users.username
          FROM user_cards
          INNER JOIN cards ON user_cards.card_id = cards.id
          INNER JOIN users ON user_cards.user_id = users.id
          WHERE user_cards.is_for_sale = 1";
$stmt = $pdo->query($query);
$cards_for_sale = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="market-hero" style="text-align: center; padding: 40px 20px;">
    <h1>Marché des cartes</h1>
    <p>Découvrez les cartes mises en vente par la communauté.</p>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green; margin-top: 10px; font-weight: bold;">Votre carte a été mise en vente avec succès !</p>
    <?php endif; ?>
    <?php if ($achat_ok): ?>
        <p style="color: green; margin-top: 10px; font-weight: bold;"><?php echo $achat_ok; ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color: red; margin-top: 10px; font-weight: bold;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Filtre par rareté (JS) -->
    <div style="margin-top: 20px;">
        <label for="rarity-filter" style="font-weight: 500;">Filtrer par rareté :</label>
        <select id="rarity-filter" style="padding: 8px 15px; margin-left: 10px; background: var(--bg-input); border: 1px solid rgba(176, 38, 255, 0.2); border-radius: 8px; color: var(--text-primary); font-family: var(--font-body); font-size: 0.9rem; cursor: pointer;">
            <option value="toutes">Toutes</option>
            <option value="commune">Commune</option>
            <option value="rare">Rare</option>
            <option value="legendaire">Légendaire</option>
        </select>
    </div>
</section>

<section class="market-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 20px;">
    <?php if (count($cards_for_sale) > 0): ?>
        <?php foreach ($cards_for_sale as $item): ?>
            <div class="card-item" data-rarity="<?php echo htmlspecialchars($item['rarity']); ?>" style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; transition: transform 0.2s;">
                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 100%; height: auto; display: block;">
                <div class="card-info" style="padding: 15px;">
                    <h3 style="font-size: 1.1rem; margin-bottom: 5px;"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 5px;">Rareté : <?php echo htmlspecialchars($item['rarity']); ?></p>
                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">Vendeur : <?php echo htmlspecialchars($item['username']); ?></p>
                    <p style="font-weight: bold; color: var(--secondary-color); font-size: 1.2rem; margin-bottom: 10px;"><?php echo number_format($item['sale_price'], 0); ?> Crédits</p>
                    <?php if (isset($_SESSION['user_id']) && $item['seller_id'] != $_SESSION['user_id']): ?>
                        <form method="POST" action="">
                            <input type="hidden" name="buy_card_id" value="<?php echo $item['inventaire_id']; ?>">
                            <button type="submit" class="btn" style="width: 100%;">Acheter</button>
                        </form>
                    <?php elseif (isset($_SESSION['user_id']) && $item['seller_id'] == $_SESSION['user_id']): ?>
                        <span style="display: inline-block; padding: 8px; color: #999; font-size: 0.9rem;">Votre carte</span>
                    <?php else: ?>
                        <a href="auth/login.php" class="btn" style="width: 100%; display: block; text-align: center;">Acheter</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune carte en vente pour le moment.</p>
    <?php endif; ?>
</section>

<style>
    .card-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<?php include 'includes/footer.php'; ?>