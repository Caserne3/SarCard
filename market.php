<?php
include 'includes/db_connect.php';
include 'includes/header.php';

// Récupération des cartes en vente
$query = "SELECT cards.name, cards.image_url, cards.rarity, user_cards.sale_price, users.username 
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
</section>

<section class="market-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 20px;">
    <?php if (count($cards_for_sale) > 0): ?>
        <?php foreach ($cards_for_sale as $item): ?>
            <div class="card-item" style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; transition: transform 0.2s;">
                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 100%; height: auto; display: block;">
                <div class="card-info" style="padding: 15px;">
                    <h3 style="font-size: 1.1rem; margin-bottom: 5px;"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 5px;">Rareté : <?php echo htmlspecialchars($item['rarity']); ?></p>
                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">Vendeur : <?php echo htmlspecialchars($item['username']); ?></p>
                    <p style="font-weight: bold; color: var(--secondary-color); font-size: 1.2rem; margin-bottom: 10px;"><?php echo number_format($item['sale_price'], 0); ?> Crédits</p>
                    <button class="btn" style="width: 100%;">Acheter</button>
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