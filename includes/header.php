<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = '/SarKard/';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SarKard - Vente de Cartes à Collectionner</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
</head>

<body>

    <header>
        <div class="logo">
            <a href="<?php echo $base_url; ?>index.php">Sar<span>Kard</span></a>
        </div>
        <nav>
            <ul>
                <li><a href="<?php echo $base_url; ?>index.php">Accueil</a></li>
                <li><a href="<?php echo $base_url; ?>market.php">Marché</a></li>
                <li><a href="<?php echo $base_url; ?>booster.php">Booster</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo $base_url; ?>collection.php">Ma Collection</a></li>
                    <li><a href="<?php echo $base_url; ?>credit.php">Boutique</a></li>
                    <li><a href="<?php echo $base_url; ?>profile.php">Mon Profil</a></li>

                    <li><a href="<?php echo $base_url; ?>auth/logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?php echo $base_url; ?>auth/login.php">Connexion</a></li>
                    <li><a href="<?php echo $base_url; ?>auth/register.php">Inscription</a></li>

                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>