<?php
// Démarre la session (obligatoire pour pouvoir la détruire)
session_start();

// Détruit toutes les données de session (l'utilisateur est déconnecté)
session_destroy();

// Redirige vers la page d'accueil
header("Location: ../index.php");
exit;
