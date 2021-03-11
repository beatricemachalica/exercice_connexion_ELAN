<?php

// On va démarrer une nouvelle session ou reprendre une session existante.
    session_start();

    // unset — Détruit une variable ici user
    // on pourrait utiliser destroy mais elle va détruire entièrement la session (méthode un peu radicale)
    // alors que unset() détruit la ou les variables dont le nom a été passé en argument.
    unset($_SESSION['user']);
    unset($_SESSION['error']);
    

    header("Location:page-connexion.php");
    // header — Envoie un en-tête HTTP brut
    // Location renvoie un en-tête au client, mais, en plus, 
    // il envoie un statut REDIRECT (302) au navigateur tant qu'un code statut 201 ou 3xx n'a pas été envoyé.