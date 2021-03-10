<?php

// on va inclure et exécuter les fichiers spécifiés en argument.
require_once("MyError.php");

// On va démarrer une nouvelle session ou reprendre une session existante.
session_start();

?>


<!-- code HTML à partir d'ici, avec un formulaire d'inscription -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
      integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
      crossorigin="anonymous"
    />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;1,100&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./css/style.css"/>
    <title>Inscription</title>
</head>
<body>
<main>
      <!-- include le header ici -->
      <?php include("SRC/header.php"); ?>

    <!-- message d'erreur qu'on récupère dans l'URL ($_GET) s'il existe (isset) -->
    <p>
        <?php
            if(isset($_GET['error'])){
                echo "<strong>".$_SESSION['error']."</strong>";
            }
        ?>
    </p>
    
    <!-- formulaire d'inscription qui est envoyé vers add-user.php -->
    
    <div class="inscriptionForm">
    <p>Inscription</p>
    <form action="add-user.php" method="POST">
    <input type="text" placeholder="Votre nom d'utilisateur" name="username" required>

    <input type="password" placeholder="Votre mot de passe" name="password" required>

    <!-- on demande le mdp une seconde fois pour s'assurer qu'il n'y a pas d'erreurs -->
    <input type="password" placeholder="Confirmez votre mot de passe" name="verifpassword" required>

    <!-- ci-dessous un token pour se protéger de la faille CSRF -->
    <input type="hidden"  name="token" value="<?= $_SESSION['token']?>" >

    <input type="submit"  value="Inscription"  >

    </form>
    <p>
        Déjà membre ? <a href="page-connexion.php" class="lienRedirection">connexion<i class="fas fa-arrow-right"></i></a>
    </p>
    </div>
</main>
</body>
</html>