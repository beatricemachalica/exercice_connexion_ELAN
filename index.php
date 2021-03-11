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

// isset — Détermine si une variable est déclarée et est différente de null
// !isset = variable non déclarée et null

if (!isset($_SESSION['error']))
$_SESSION['error'] = new MyError();

?>

<!-- code HTML de la page à partir d'ici -->
<!-- l'index est ce qu'on appelle un "controleur frontal" -->
<!-- Le contrôleur frontal va nous permettre d'intercepter toutes les requêtes et de renvoyer une réponse. 
On va donc faire appel à index. php qui va jouer ce rôle et nous donner la page que l'on souhaite en fonction de notre besoin. -->

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
    <link rel="stylesheet" href="./css/style.css"/>
    <title>Le Club des Randonneurs</title>
  </head>
  <body>
    <main class="indexMain">
      
      <header class="indexHeader header flex">
        <h1>Le Club <span> des Randonneurs</span></h1>
        <p>Bienvenue <?= ucwords($_SESSION['user']['username']) ?> !</p>
        <!-- ucwords — Met en majuscule la première lettre de tous les mots -->
        <a class="deconnexion" href='logout.php'>Deconnexion</a>
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
      // si la $_SESSION['user'] est non déclarée et null alors on redirige vers la page de connexion
      if(!isset($_SESSION['user'])){
        header("Location:page-connexion.php");
      }
      ?>

    </main>
  </body>
</html>
