<?php

include_once 'Sede.php';
include_once 'Organizzatore.php';

/**
 * Rappresenta un viaggio, che viene organizzato da un Organizzatore in
 * una Sede a cui appartiene. Un Utente puo prenotare il viaggio.
 *
 */
class Viaggio {

    /**
     * La data di partenza del viaggio
     * @var DateTime 
     */
    private $dataPartenza;
     /**
     * La data di rientro del viaggio
     * @var DateTime 
     */
    private $dataRitorno;
    /**
     * Lista degli identificatori dei prenotati
     * @var array 
     */
    private $prenotati;

    /**
     * Quanti utenti si possono prenotare al massimo per questo viaggio
     * @var int
     */
    private $capienza;

    /**
     * Costo del viaggio
     * @var float
     */
    private $prezzo;

    /**
     * La Sede in cui si svolge il viaggio
     * @var Sede
     */
    private $sede;
    
    /**
     * L Organizzatore che ha organizzato il viaggio
     * @var Organizzatore
     */
    private $organizzatore;
    
    /**
     * Identificatore del viaggio
     * @var int
     */
    private $id;

    
    /**
     * Costrutture del viaggio
     */
    public function __construct() {
        $this->prenotati = array();
    }

    /**
     * Restituisce l'indentificatore del viaggio
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Modifica il valore dell'identificatore 
     * @param int $id il nuovo id per il viaggio
     * @return boolean true se il valore e' stato modificato, 
     *                 false altrimenti
     */
    public function setId($id) {
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intVal)) {
            return false;
        }
        $this->id = $intVal;
        return true;
    }

    /**
     * Restituisce la data di partenza del viaggio
     * @return DateTime
     */
    public function getDataPartenza() {
        return $this->dataPartenza;
    }

    /**
     * Modifica il valore della data di partenza del viaggio
     * @param DateTime $dataPartenza il nuovo valore della data di partenza
     * @return boolean true se il nuovo valore della data e' stato impostato,
     * false nel caso il valore non sia ammissibile
     */
    public function setDataPartenza(DateTime $dataPartenza) {
        $this->dataPartenza = $dataPartenza;

    }

    /**
     * Restituisce la data di ritorno del viaggio
     * @return DateTime
     */
    public function getDataRitorno() {
        return $this->dataRitorno;
    }

    /**
     * Modifica il valore della data di ritorno del viaggio
     * @param DateTime $dataRitorno il nuovo valore della data di ritorno
     * @return boolean true se il nuovo valore della data e' stato impostato,
     * false nel caso il valore non sia ammissibile
     */
    public function setDataRitorno(DateTime $dataRitorno) {
        $this->dataRitorno = $dataRitorno;
    }
    
    /**
     * Prenota un utente ad un viaggio
     * @param int $utente_id identificatore dell utente da prenotare
     * @return boolean true se la prenotazione e' andata a buon fine, false altrimenti
     */
    public function prenota($utente_id) {
        if (count($this->prenotati) >= $this->capienza) {
            return false;
        }
        $this->prenotati[] = $utente_id;
        return true;
    }

    /**
     * Rimuove la prenotazione di un utente dal viaggio
     * @param Utente $utente l utente da cancellare
     * @return boolean true se la prenotazione e' stata cancellata, false altrimenti
     * es. quando l utente non era stato prenotato precedentemente
     */
    public function cancella(Utente $utente) {

        $pos = $this->posizione($utente);
        if ($pos > -1) {
            array_splice($this->prenotati, $pos, 1);
            return true;
        }

        return false;
    }

    /**
     * Restituisce la lista dei prenotati (per riferimento)
     * @return array
     */
    public function &getPrenotati() {
        return $this->prenotati;
    }
    
    /**
     * Restituisce il numero di prenotati
     * @return int
     */
    public function getNumeroPrenotati(){
        return count($this->prenotati);
    }

   /**
     * Restituisce il prezzo del viaggio
     * @return float
     */
    public function getPrezzo() {
        return $this->prezzo;
    }

    /**
     * Modifica il prezzo del viaggio 
     * @param float $prezzo il nuovo prezzo per il viaggio
     * @return boolean true se il valore e' stato modificato, 
     *                 false altrimenti
     */
    public function setPrezzo($prezzo) {
        $floatVal = filter_var($prezzo, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        if (!isset($floatVal)) {
            return false;
        }
        $this->prezzo = $floatVal;
        return true;
    }

    /**
     * Restituisce il numero massimo di prenotati per il viaggio
     * @return int
     */
    public function getCapienza() {
        return $this->capienza;
    }

    /**
     * Modifica il valore massimo per il numero di prenotati al viaggio
     * @param int $capienza la nuova capienza del viaggio
     * @return boolean true se il valore e' stato impostato correttamente, false
     * altrimenti (per esempio se ci sono gia' piu' prenotati del valore passato)
     */
    public function setCapienza($capienza) {
        $intVal = filter_var($capienza, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intVal)) {
            return false;
        }
        if ($intVal < count($this->prenotati)) {
            return false;
        }
        $this->capienza = $intVal;
        return true;
    }

    /**
     * Restiuisce il numero di posti ancora disponibili per il viaggio
     * @return int 
     */
    public function getPostiLiberi() {
        return $this->capienza - count($this->prenotati);
    }

    /**
     * Verifica se un utente sia gia' nella lista di prenotati o meno
     * @param Utente $utente l utente da ricercare
     * @return boolean true se e' gia' in lista, false altrimenti
     */
    public function inLista(Utente $utente) {
        $pos = $this->posizione($utente);
        if ($pos > -1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Restituisce la Sede del viaggio
     * @return Sede
     */
    public function getSede() {
        return $this->sede;
    }

    /**
     * Imposta la Sede per il viaggio
     * @param Sede $sede la nuova sede
     */
    public function setSede(Sede $sede) {
        $this->sede = $sede;
    }

    /**
     * Restituisce l Organizzatore del viaggio
     * @return Organizzatore
     */
    public function getOrganizzatore() {
        return $this->organizzatore;
    }

    /**
     * Imposta l Organizzatore per il viaggio
     * @param Organizzatore $organizzatore il nuovo organizzatore
     */
    public function setOrganizzatore(Organizzatore $organizzatore) {
        $this->organizzatore = $organizzatore;
    }

    /**
     * Calcola la posizione di un utente all'interno della lista
     * @param Utente $utente l utente da ricercare
     * @return int la posizione dell utente nella lista, -1 se non e' stato 
     * inserito
     */
    private function posizione(Utente $utente) {
        for ($i = 0; $i < count($this->prenotati); $i++) {
            if ($this->prenotati[$i]==($utente->getId())) {
                return $i;
            }
        }
        return -1;
    }

}

?>
