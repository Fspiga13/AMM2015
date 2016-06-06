<?php

include_once 'User.php';

/**
 * Classe che rappresenta un Utente
 * 
 */
class Utente extends User {

    /**
     * Costruttore della classe
     */
    public function __construct() {
        // richiamiamo il costruttore della superclasse
        parent::__construct();
        $this->setRuolo(User::Utente);
        
    }
}

?>
