<?php

// identique à require mais là PHP va vérifier si le fichier a déjà été inclus,
// et si c'est le cas, ne l'inclut pas une deuxième fois.
// require = include mais l'erreur est différente
// include -> inclut et exécute le fichier spécifié en argument.
require_once("MyError.php");

// $_SESSION est une superglobale tout comme $_POST, $_GET etc.
// Les Superglobales sont des variables internes qui sont toujours disponibles, quel que soit le contexte.
// $_SESSION : Un tableau associatif des valeurs stockées dans les sessions, et accessible au script courant.
// session_start() Démarre une nouvelle session ou reprend une session existante.
session_start();

// Créer un token pour se protéger de la faille CSRF :

// On va générer un token à l'aide de bin2hex et random_bytes
// bin2hex — Convertit des données binaires en représentation hexadécimale
// random_bytes — Génère des octets pseudo-aléatoire cryptographiquement sécurisé
// possible aussi d'utiliser une autre méthode : OAuthProvider::generateToken — qui génère un jeton aléatoire 
$_SESSION["token"] = bin2hex(random_bytes(24));

// isset — Détermine si une variable est déclarée et est différente de null
// !isset = variable non déclarée et null

if (!isset($_SESSION['error']))
$_SESSION['error'] = new MyError();

?>

<!-- code HTML à partir d'ici, avec un formulaire de connexion -->

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
    <link rel="stylesheet" href="./css/style.css" />
    <title>Le Club des Randonneurs</title>
  </head>
  <body>
    <main>
      
      <header class="header">
        <h1>Le Club <span> des Randonneurs</span></h1>
      </header>

      <!-- message d'erreur qu'on récupère dans l'URL s'il existe (isset) -->
      <p>
        <?php
            if(isset($_GET['error'])){
                echo "<strong>".$_SESSION['error']."</strong>";
            }
        ?>
      </p>

      <?php
      // si la $_SESSION['user'] est déclarée et non null alors on redirige vers la page d'accueil
      if(isset($_SESSION['user'])){
        header("Location:index.php");
      }
      ?>

      <!-- formulaire de connexion qui est envoyé vers login.php -->
      <div class="login">
        <form action="login.php" method="POST">
          <p>
            Connexion
          </p>
          <input
            type="text"
            placeholder="Nom d'utilisateur"
            name="username"
            required
          />
          <input
            type="password"
            placeholder="Mot de passe"
            name="password"
            required
          />
          <!-- ci-dessous un token pour se protéger de la faille CSRF -->
          <p><input type="hidden"   value="<?= $_SESSION["token"]?>" name="token" ></p>
          <input type="submit" value="Connexion" />
        </form>
        <p>
          Pas encore membre ? <a href="inscription.php" class="lienRedirection">inscription<i class="fas fa-arrow-right"></i></a>
        </p>
      </div>
    </main>
  </body>
</html>
