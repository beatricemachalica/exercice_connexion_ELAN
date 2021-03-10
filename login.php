<?php

    // on va inclure et exécuter les fichiers spécifiés en argument.
    require_once("connect.php");
    require_once("Controller.php");
    require_once("MyError.php");

    // On va démarrer une nouvelle session ou reprendre une session existante.
    session_start();

    // création d'un nouvel objet de la classe Controller
    $controler = new Controller($connexion);

    // On peut utiliser la première méthode avec htmlentities ci-desous :

    // $name = htmlentities(trim($_POST['username']));
    // $pwd  = htmlentities(trim($_POST['password']));

    // Ou on peut utiliser la seconde méthode avec filter_input & Regex :

    // filter_input — Récupère une variable externe et la filtre
        // en paramètres : type de la constante ici INPUT_POST, puis nom de la variable ici 'username',
        // puis ID du filtre à appliquer ici FILTER_SANITIZE_STRING :
        // Il supprime les balises, et supprime ou encode les caractères spéciaux.
        // options : FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH
        // FILTER_FLAG_STRIP_LOW : "Strips characters that have a numerical value <32."
        // FILTER_FLAG_STRIP_HIGH : "Strips characters that have a numerical value >127."
    // Attention ! on n'utilise plus htmlspecialchars()

    $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) ;

    if (is_string($name)) {

        $pwd = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) ;

        $token = filter_input(INPUT_POST, 'token', FILTER_VALIDATE_REGEXP, [
            "options" => [
                "regexp" => '#^[A-Fa-f0-9]{48}$#'
            ]
        ]);

        // on utilise la méthode getUser de la classe Controller
        $user = $controler->getUser($name);

        if (is_array($user)) {

            // on utilise la méthode verifyPassword de Controller
            if ($controler->verifyPassword($pwd)) {

                // hash_equals — Comparaison de chaînes résistante aux attaques temporelles
                // Compare deux chaînes en utilisant la même durée qu'elles soient égales ou non.
                // Cette fonction devrait être utilisée pour limiter les attaques temporelles.
                if (hash_equals($_SESSION['token'], $token)) {

                    $_SESSION['user'] = $user;
                    header("Location:index.php");
                    // header — Envoie un en-tête HTTP brut
                    // Location renvoie un en-tête au client, mais, en plus, 
                    // il envoie un statut REDIRECT (302) au navigateur tant qu'un code statut 201 ou 3xx n'a pas été envoyé.

                } else {
                    // on utilise la méthode setError de la classe MyError pour créer et afficher une erreur.
                    // puis on redirige vers index.php en ajoutant ?error dans l'URL
                    // pour qu'il soit récuperer par $_GET par le message dans l'index
                    $_SESSION['error']->setError(-8, "Identification incorrecte ! Veuillez réessayer...") ;
                    header("Location:index.php?error");

                }

            } else {
                // idem
                $_SESSION['error']->setError(-7, "Identification incorrecte ! Veuillez réessayer...") ;
                header("Location:index.php?error");

            }

        } else {
            // idem
            $_SESSION['error']->setError(-6, "Identification incorrecte ! Veuillez réessayer...") ;
            header("Location:index.php?error");

        }

    } else {
        // idem
        $_SESSION['error']->setError(-5, "Identification incorrecte ! Veuillez réessayer...") ;
        header("Location:index.php?error");

    }
