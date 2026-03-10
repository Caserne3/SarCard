<?php
// --- Connexion BDD et Header ---
include 'includes/db_connect.php';
include 'includes/header.php';

// --- TÂCHE 1 : Vérification que l'utilisateur est connecté ---
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// --- Requête SQL : Récupérer toutes les cartes du joueur connecté ---
// On relie user_cards (l'inventaire) à cards (les infos de la carte)
$stmt = $pdo->prepare("SELECT user_cards.id AS inventaire_id, cards.name, cards.image_url, cards.rarity, user_cards.is_for_sale
                        FROM user_cards
                        INNER JOIN cards ON user_cards.card_id = cards.id
                        WHERE user_cards.user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$mes_cartes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ==================== TÂCHE 2 : AFFICHAGE HTML ==================== -->

<section style="text-align: center; padding: 40px 20px;">
    <h1>Ma Collection</h1>
    <p>Vous possédez <?php echo count($mes_cartes); ?> carte(s).</p>
</section>

<section style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 20px;">

    <?php if (count($mes_cartes) > 0): ?>
        <?php foreach ($mes_cartes as $carte): ?>
            <div class="card-item" style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; transition: transform 0.2s;">

                <!-- Image de la carte -->
                <img src="<?php echo htmlspecialchars($carte['image_url']); ?>"
                    alt="<?php echo htmlspecialchars($carte['name']); ?>"
                    style="width: 100%; height: auto; display: block;">

                <div style="padding: 15px;">
                    <!-- Nom de la carte -->
                    <h3 style="font-size: 1.1rem; margin-bottom: 5px;">
                        <?php echo htmlspecialchars($carte['name']); ?>
                    </h3>

                    <!-- Rareté -->
                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">
                        Rareté : <?php echo htmlspecialchars($carte['rarity']); ?>
                    </p>

                    <!-- TÂCHE 3 : Bouton conditionnel selon is_for_sale -->
                    <?php if ($carte['is_for_sale'] == 1): ?>
                        <!-- La carte est déjà en vente -->
                        <span style="display: inline-block; padding: 8px 15px; background: #f39c12; color: white; border-radius: 5px; font-size: 0.9rem;">
                            📢 En vente sur le marché
                        </span>
                    <?php else: ?>
                        <!-- La carte n'est pas en vente → lien vers sell.php -->
                        <a href="sell.php?id=<?php echo $carte['inventaire_id']; ?>"
                            class="btn" style="display: block; text-align: center;">
                            Vendre cette carte
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Vous n'avez aucune carte. <a href="booster.php" style="color: var(--secondary-color);">Ouvrir un booster ?</a></p>
    <?php endif; ?>

</section>

<style>
    .card-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<?php include 'includes/footer.php'; ?>