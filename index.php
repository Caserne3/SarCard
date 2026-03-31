<?php include 'includes/header.php'; ?>

</main>

<section class="hero" style="position: relative; overflow: hidden; min-height: 85vh; display: flex; align-items: center; justify-content: center; width: 100%; max-width: 100%; margin: 0; padding: 0;">

    <img src="<?php echo $base_url; ?>assets/img/fond/fond.gif" alt=""
        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; opacity: 0.99;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, rgba(11,11,26,0.2), rgba(11,11,26,0.85)); z-index: 1;"></div>
    <div style="position: relative; z-index: 2; text-align: center; padding: 40px 20px;">
        <h1>Bienvenue sur SarKard</h1>
        <p>Achetez et collectionnez, et surtout payez.</p>
        <br>
        <a href="market.php" class="btn">Voir le marché</a>
    </div>
</section>

<main>

    <?php include 'includes/footer.php'; ?>