<?php

// création d'une classe controller :
// son but sera de "prepare" et "bindParam" les requêtes SQL.
// pour gagner en performance et se protéger des failles d'injection SQL

 class Controller{

    // propriétés de la classe
    private $_connexion;
    private $_user;

    // constructeur de la classe
    public function __construct($connexion){
            $this->_connexion = $connexion;
    }

    // les méthodes de la classe ci-dessous :
    
    // getUser pour récupérer le "username",
    // verifyPassword pour vérifier un mot de passe,
    // addUser pour ajouter un nouveau utilisateur.

    public function  getUser($uname){

        try{
            // ci-dessous la requête SQL
            $sql = "SELECT username, password FROM user WHERE username = LOWER(:name)"; // :name est un marqueur de nommage

            // préparation de la requête -> prepare() Prépare une requête à l'exécution et retourne un objet.
            $statement = $this->_connexion->prepare($sql);
         
            // injection des paramètres, le :name qu'il va chercher ce sera le $uname
            $statement->bindParam("name", $uname);

           // execute la requête, retourne TRUE sinon l'exception sera catch plus loin
            $statement->execute();
           
            // on récupère l'utilisateur en base de données
            // fetch() récupère une ligne depuis un jeu de résultats associé à l'objet PDOStatement.
            $this->_user = $statement->fetch();
            return $this->_user;
        }

        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function verifyPassword($upwd){
        sleep(1);
        return password_verify($upwd, $this->_user['password']);
        // password_verify — Vérifie que le hachage fourni correspond bien au mot de passe fourni.
    }

    public function addUser($uname, $upwd){

        try{

            // ci-dessous la requête SQL
            $sql = "INSERT INTO user (username, password) VALUES (:name, :pwd)";

            // préparation de la requête -> prepare() Prépare une requête à l'exécution et retourne un objet.
            $statement = $this->_connexion->prepare($sql);

            // injection des paramètres, le :name qu'il va chercher ce sera le $uname
            $statement->bindParam("name", $uname);

            // injection des paramètres, mais pour le mot de passe
            $statement->bindParam("pwd", $upwd);
        
           // execute la requête, retourne TRUE sinon l'exception sera catch plus loin
            return $statement->execute();

        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
 }

 ?>