<?php
    // on va inclure et exécuter les fichiers spécifiés en argument.
    require_once("connect.php");
    require_once("Controller.php");
    require_once("MyError.php");

    // On va démarrer une nouvelle session ou reprendre une session existante.
    session_start();

    // création d'un nouvel objet de la classe Controller
    $controler = new Controller($connexion);

    // on va se protéger contre les failles XSS du form :
    
    // première méthode : utiliser htmlentities
    // htmlentities — Convertit tous les caractères éligibles en entités HTML
    // par exemple cela va désactiver la balise <script> si l'utilisateur veut l'insérer (faille XSS)
    // $name = htmlentities(trim($_POST['username'])) ;
    // $pwd1 = htmlentities(trim($_POST['password'])) ;
    // $pwd2 = htmlentities(trim($_POST['pwdverif'])) ;

    // seconde méthode : avec filter_input et REGEXP
    // filter_input — Récupère une variable externe et la filtre
        // en paramètres : type de la constante ici INPUT_POST, puis nom de la variable ici 'username',
        // puis ID du filtre à appliquer ici FILTER_VALIDATE_REGEXP = Valide une valeur avec une expression rationnelle regexp.
        // REGEX = Les expressions régulières
        // enfin, options => Tableau associatif d'options ou des drapeaux.
    // Attention ! on n'utilise plus htmlspecialchars()
    $name = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_REGEXP, [
        "options" => [
            "regexp" => '#^[A-Za-z][A-Za-z0-9_-]{5,31}$#'
        ]
    ]);

    // is_string — Détermine si une variable est de type chaîne de caractères, 
    // retourne true si value est une chaîne de caractères, false sinon.
    if (is_string($name)) {

        $pwd1 = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, [
            "options" => [
                "regexp" => '#^.*(?=.{8,63})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).*$#'
            ]
        ]);

        if (is_string($pwd1)) {

            // on utilise la méthode getUser de la classe Controller
            $user = $controler->getUser($name);
            

            if (is_array($user)) {

                $_SESSION['error']->setError(-3, "Cet identifiant est déjà pris ! Veuillez en choisir un autre...") ;
                header("Location:inscription.php?error");
                // on utilise la méthode setError de la classe MyError pour créer et afficher une erreur.
                // puis on redirige vers index.php en ajoutant ?error dans l'URL
                // pour qu'il soit récuperer par $_GET par le message dans l'index

            } else {

                $pwd2 = filter_input(INPUT_POST, 'verifpassword', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) ;

                // on va vérifier que le mdp entré deux fois est identique
                if ($pwd1 === $pwd2) {

                    // pour la RGBD et pour une question de sécurité 
                    // on va hash le mdp soit avec PASSWORD_BCRYPT soit avec PASSWORD_ARGON2I
                    // password_hash — Crée une clé de hachage pour un mot de passe
                    $status = $controler->addUser(strtolower($name), password_hash($pwd1, PASSWORD_ARGON2I)) ;

                    if ($status) {

                        header("Location:page-connexion.php");

                    } else {

                        $_SESSION['error']->setError(-9, "Erreur inconnue ! Veuillez réessayer.") ;
                        header("Location:inscription.php?error");
                        // idem
                    }
                } else {

                    $_SESSION['error']->setError(-4, "Les deux mots de passe saisis ne concordent pas ! Veuillez réessayer...") ;
                    header("Location:inscription.php?error");
                    // idem
                }

           }

        } else {

            $_SESSION['error']->setError(-2, "Le mot de passe doit comporter au moins 8 caractères, et contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial ! Veuillez réessayer...") ;
            header("Location:inscription.php?error");
            // idem
        }

    } else {

        $_SESSION['error']->setError(-1, "Le nom d'utilisateur doit comporter entre 6 et 32 caractères alphanumériques, et commencer par une lettre ! Veuillez réessayer...") ;
        header("Location:inscription.php?error");
        // idem
    }


