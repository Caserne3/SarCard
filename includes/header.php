<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SarKard - Vente de Cartes à Collectionner</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <header>
        <div class="logo">
            <a href="index.php">Sar<span>Kard</span></a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="market.php">Marché</a></li>
                <li><a href="booster.php">Booster</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="collection.php">Ma Collection</a></li>
                    <li><a href="profile.php">Mon Profil</a></li>

                    <li><a href="auth/logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="auth/login.php">Connexion</a></li>
                    <li><a href="auth/register.php">Inscription</a></li>

                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>