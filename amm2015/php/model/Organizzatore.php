<?php

include_once 'User.php';

/**
 * Classe che rappresenta un Organizzatore
 *
 */
class Organizzatore extends User {


    /**
     * Costruttore
     */
    public function __construct() {
        // richiamiamo il costruttore della superclasse
        parent::__construct();
        $this->setRuolo(User::Organizzatore);
    }
}

?>
