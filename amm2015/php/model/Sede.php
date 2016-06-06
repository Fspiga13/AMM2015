<?php
/**
 * Classe che rappresenta una Sede
 *
 */
class Sede {

    /**
     * La Nazione della Sede
     * @var string 
     */
    private $nazione;
    /**
     * La Citta della Sede
     * @var string 
     */
    private $citta;
    /**
     * L'identificatore della Sede
     * @var int
     */
    private $id;

    
    /**
     * Costrutture di una Sede
     */
    public function __construct() {
        
    }

    /**
     * Imposta la Nazione di una Sede
     * @param string $nazione la nuova Nazione per la Sede
     */
    public function setNazione($nazione){
        $this->nazione = $nazione;
    }
    
    /**
     * Restituisce la Nazione di una Sede
     * @return string
     */
    public function getNazione() {
        return $this->nazione;
    }

    /**
     * Imposta la Citta di una Sede
     * @param string $citta la nuova Citta per la Sede
     */
    public function setCitta($citta){
        $this->citta = $citta;
    }
    
    /**
     * Restituisce la Citta di una Sede
     * @return string
     */
    public function getCitta() {
        return $this->citta;
    }

    /**
     * Restituisce l'identificatore della Sede
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Imposta un nuovo identificatore per la Sede
     * @param int $id
     */
    public function setId($id){
        $this->id = $id;
    }

    /**
     * Verifica se due oggetti Sede sono logicamente uguali
     * @param Sede $other l'oggetto con cui confrontare $this
     * @return boolean true se i due oggetti sono logicamente uguali, false 
     * altrimenti
     */
    public function equals(Sede $other) {
        return $other->id == $this->id;
    }
    
    

}

?>
