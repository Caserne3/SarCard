<?php
include 'includes/db_connect.php';
include 'includes/header.php';

// --- Verif connection user ---
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$success_message = '';

// --- Traitement de l'achat de crédits ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy_credits'])) {
    $amount = intval($_POST['amount']);

    // Sécurité : seuls ces 4 montants sont autorisés
    $montants_autorises = [50, 100, 200, 500];

    if (in_array($amount, $montants_autorises)) {
        // Ajouter les crédits au joueur
        $stmt = $pdo->prepare("UPDATE users SET credits = credits + :amount WHERE id = :id");
        $stmt->execute([
            'amount' => $amount,
            'id'     => $_SESSION['user_id']
        ]);
        $success_message = "Achat validé ! " . $amount . " crédits ont été ajoutés à votre compte.";
    } else {
        $success_message = "Montant invalide.";
    }
}

// Récupérer le solde actuel
$stmt = $pdo->prepare("SELECT credits FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$solde = $stmt->fetch(PDO::FETCH_ASSOC)['credits'];

// Les 4 packs disponibles
$packs = [
    ['credits' => 50,  'prix' => '0.99€',  'desc' => '1 Booster',   'icon' => '🎴'],
    ['credits' => 100, 'prix' => '1.99€',  'desc' => '2 Boosters',  'icon' => '🎴🎴'],
    ['credits' => 200, 'prix' => '3.49€',  'desc' => '4 Boosters',  'icon' => '🃏'],
    ['credits' => 500, 'prix' => '7.99€',  'desc' => '10 Boosters', 'icon' => '💎'],
];
?>

<section style="text-align: center; padding: 40px 20px;">
    <h1>Boutique — Acheter des Crédits</h1>
    <p style="margin: 15px 0;">Votre solde : <strong style="color: var(--neon-pink);"><?php echo number_format($solde, 0); ?> Crédits</strong></p>

    <?php if ($success_message): ?>
        <p style="color: green; margin-top: 10px; font-weight: bold;"><?php echo $success_message; ?></p>
    <?php endif; ?>
</section>

<section class="market-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; padding: 0 20px 40px;">
    <?php foreach ($packs as $pack): ?>
        <div class="card-item" style="background: var(--bg-card); border: 1px solid rgba(176, 38, 255, 0.15); border-radius: 12px; overflow: hidden; text-align: center; padding: 30px 20px;">

            <div style="font-size: 3rem; margin-bottom: 15px;">
                <?php echo $pack['icon']; ?>
            </div>

            <h2 style="font-size: 1.8rem; margin-bottom: 5px;">
                <?php echo $pack['credits']; ?> Crédits
            </h2>

            <p style="font-size: 1rem; margin-bottom: 5px;">
                <?php echo $pack['desc']; ?>
            </p>

            <p style="font-size: 1.5rem; font-weight: 700; color: var(--neon-cyan); margin: 15px 0;">
                <?php echo $pack['prix']; ?>
            </p>

            <form method="POST" action="">
                <input type="hidden" name="amount" value="<?php echo $pack['credits']; ?>">
                <button type="submit" name="buy_credits" class="btn" style="width: 100%; padding: 14px;">
                    Acheter
                </button>
            </form>
        </div>
    <?php endforeach; ?>
</section>

<?php include 'includes/footer.php'; ?>
