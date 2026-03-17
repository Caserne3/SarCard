<?php
include 'includes/db_connect.php';
include 'includes/header.php';

// --- Vérification : l'utilisateur doit être connecté ---
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// --- Variables pour les messages ---
$message = '';
$message_color = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['claim_reward'])) {

    // Récupérer les infos du joueur (crédits + date de dernière récompense)
    $stmt = $pdo->prepare("SELECT credits, last_reward_date FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si la récompense est disponible
    $peut_reclamer = false;

    if ($user['last_reward_date'] === null) {
        $peut_reclamer = true;
    } else {

        $derniere_recompense = strtotime($user['last_reward_date']);
        $maintenant = strtotime(date('Y-m-d H:i:s'));
        $difference_secondes = $maintenant - $derniere_recompense;
        $douze_heures = 12 * 60 * 60; // 12h = 43200 secondes

        if ($difference_secondes >= $douze_heures) {
            $peut_reclamer = true;
        }
    }

    if ($peut_reclamer) {
        $stmt = $pdo->prepare("UPDATE users SET credits = credits + 50, last_reward_date = :date WHERE id = :id");
        $stmt->execute([
            'date' => date('Y-m-d H:i:s'),
            'id'   => $_SESSION['user_id']
        ]);
        $message = "Félicitations, +50 crédits !";
        $message_color = "green";
    } else {
        $secondes_restantes = $douze_heures - $difference_secondes;
        $heures_restantes = floor($secondes_restantes / 3600);
        $minutes_restantes = floor(($secondes_restantes % 3600) / 60);
        $message = "Revenez dans " . $heures_restantes . "h " . $minutes_restantes . "min.";
        $message_color = "red";
    }
}

// --- Récupérer les infos à jour du joueur pour l'affichage ---
$stmt = $pdo->prepare("SELECT username, credits, last_reward_date FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// --- Calculer si le bouton doit être actif ou grisé ---
$bouton_actif = false;
if ($user['last_reward_date'] === null) {
    $bouton_actif = true;
} else {
    $derniere_recompense = strtotime($user['last_reward_date']);
    $maintenant = strtotime(date('Y-m-d H:i:s'));
    $difference_secondes = $maintenant - $derniere_recompense;
    if ($difference_secondes >= 12 * 60 * 60) {
        $bouton_actif = true;
    }
}
?>

<!-- ==================== AFFICHAGE PROFIL ==================== -->

<section style="max-width: 500px; margin: 40px auto; padding: 25px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center;">

    <h1>Mon Profil</h1>

    <!-- Pseudo du joueur -->
    <p style="font-size: 1.2rem; margin: 15px 0;">
        Pseudo : <strong><?php echo htmlspecialchars($user['username']); ?></strong>
    </p>

    <!-- Solde de crédits -->
    <p style="font-size: 1.2rem; margin-bottom: 20px;">
        Solde : <strong style="color: var(--secondary-color);"><?php echo number_format($user['credits'], 0); ?> Crédits</strong>
    </p>

    <hr style="margin-bottom: 20px;">

    <h2 style="margin-bottom: 10px;">Récompense quotidienne</h2>

    <!-- Message de succès ou d'erreur -->
    <?php if ($message): ?>
        <p style="color: <?php echo $message_color; ?>; margin-bottom: 15px; font-weight: bold;">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <!-- Bouton pour réclamer la récompense -->
    <form method="POST" action="">
        <?php if ($bouton_actif): ?>
            <button type="submit" name="claim_reward" class="btn" style="padding: 12px 30px; font-size: 1.1rem;">
                Réclamer 50 crédits gratuits
            </button>
        <?php else: ?>
            <button type="button" disabled class="btn" style="padding: 12px 30px; font-size: 1.1rem; background: #999; cursor: not-allowed;">
                Récompense indisponible
            </button>
        <?php endif; ?>
    </form>
</section>

<?php include 'includes/footer.php'; ?>