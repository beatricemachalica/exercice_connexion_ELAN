<?php

// création d'une classe MyError :
// son but sera de montrer des messages d'erreur

    class MyError {

        // propriétés de la classe :
        private $_code ;
        private $_message ;
        private $_time ;

        // constructeur de la classe :
        function __construct ($code=0, $message="") {
            $this->_code = $code ;
            $this->_message = $message ;
            $this->_time = new DateTime ("NOW", new DateTimeZone("Europe/Paris")) ;
        }

        // les méthodes de la classe ci-dessous :

        // condition ternaire pour afficher un message d'erreur si le code est différent de 0
        function __toString () {
            return ($this->_code != 0) ? "[".$this->_time->format('Y-m-d H:i:s')."] Error ".$this->_code." : ".$this->_message : "" ;
        }

        function setError ($code=0, $message="") {
            $this->_code = $code ;
            $this->_message = $message ;
            $this->_time = new DateTime ("NOW", new DateTimeZone("Europe/Paris")) ;
        }
    }