<?php

include_once 'BaseController.php';
include_once basename(__DIR__) . '/../model/ViaggioFactory.php';

/**
 * Controller che gestisce la modifica dei dati dell'applicazione relativa agli 
 * Utenti da parte di user con ruolo Utente 
 *
 */
class UtenteController extends BaseController {

    const viaggi = 'viaggi';

    /**
     * Costruttore
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Metodo per gestire l'input dell'utente. 
     * @param type $request la richiesta da gestire
     */
    public function handleInput(&$request) {

        // creo il descrittore della vista
        $vd = new ViewDescriptor();


        // imposto la pagina
        $vd->setPagina($request['page']);

        // imposto il token per impersonare un utente (nel lo stia facendo)
        $this->setImpToken($vd, $request);

        // gestion dei comandi
        // tutte le variabili che vengono create senza essere utilizzate 
        // direttamente in questo switch, sono quelle che vengono poi lette
        // dalla vista, ed utilizzano le classi del modello

        if (!$this->loggedIn()) {
            // utente non autenticato, rimando alla home

            $this->showLoginPage($vd);
        } else {
            // utente autenticato
            $user = UserFactory::instance()->cercaUserPerId(
                            $_SESSION[BaseController::user], $_SESSION[BaseController::role]);


            // verifico quale sia la sottopagina della categoria
            // Docente da servire ed imposto il descrittore 
            // della vista per caricare i "pezzi" delle pagine corretti
            // tutte le variabili che vengono create senza essere utilizzate 
            // direttamente in questo switch, sono quelle che vengono poi lette
            // dalla vista, ed utilizzano le classi del modello
            if (isset($request["subpage"])) {
                switch ($request["subpage"]) {

                    // modifica dei dati anagrafici
                    case 'anagrafica':
                        $vd->setSottoPagina('anagrafica');
                        break;

                    // visualizzazione dei viaggi prenotati
                    case 'prenotazioni':
                        $prenotazioni = ViaggioFactory::instance()->getViaggiPrenotatiPerUtente($user);
                        $vd->setSottoPagina('prenotazioni');
                        break;

                    // visualizzazione dei viaggi disponibili
                    case 'viaggi':
                        // carichiamo i viaggi dal db
                        $viaggi = ViaggioFactory::instance()->getViaggiPrenotabiliPerUtente($user);
                        $vd->setSottoPagina('viaggi');
                        break;
                    default:

                        $vd->setSottoPagina('home');
                        break;
                }
            }



            // gestione dei comandi inviati dall'utente
            if (isset($request["cmd"])) {
                // abbiamo ricevuto un comando
                switch ($request["cmd"]) {

                    // logout
                    case 'logout':
                        $this->logout($vd);
                        break;

                    // aggiornamento indirizzo
                    case 'indirizzo':

                        // in questo array inserisco i messaggi di 
                        // cio' che non viene validato
                        $msg = array();
                        $this->aggiornaIndirizzo($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Indirizzo aggiornato");
                        $this->showHomeUtente($vd);
                        break;

                    // cambio email
                    case 'email':
                        // in questo array inserisco i messaggi di 
                        // cio' che non viene validato
                        $msg = array();
                        $this->aggiornaEmail($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Email aggiornata");
                        $this->showHomeUtente($vd);
                        break;

                    // cambio password
                    case 'password':
                        // in questo array inserisco i messaggi di 
                        // cio' che non viene validato
                        $msg = array();
                        $this->aggiornaPassword($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Password aggiornata");
                        $this->showHomeUtente($vd);
                        break;

                    // prenotazione ad un viaggio
                    case 'prenota':
                        // recuperiamo l'indice 
                        $msg = array();
                        $a = $this->getViaggioPerIndice($viaggi, $request, $msg);
                        if (isset($a)) {
                            $isOk = $a->prenota($user);
                            if ($isOk) {
                                $count = ViaggioFactory::instance()->aggiungiPrenotazione($user, $a);
                            }
                            if (!$isOk || $count != 1) {
                                $msg[] = "<li> Impossibile prenotarti al viaggio specificato. Verifica la capienza del viaggio </li>";
                            }
                        } else {
                            $msg[] = "<li> Impossibile prenotarti al viaggio specificato. Verifica la capienza del viaggio </li>";
                        }

                        $this->creaFeedbackUtente($msg, $vd, "Ti sei prenotato al viaggio specificato!");
                        $this->showHomeUtente($vd);
                        break;

                    // cancellazione da un viaggio
                    case 'cancella':
                        
                        if (isset($request['prenotazione'])) {
                            $intVal = filter_var($request['prenotazione'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($intVal)) {
                                $mod_viaggio = ViaggioFactory::instance()->cercaViaggioPerId($intVal);
                                if ($mod_viaggio != null) {
                                    if (ViaggioFactory::instance()->cancellaPrenotazione($user, $mod_viaggio) != 1) {
                                        $msg[] = '<li> Impossibile cancellare la prenotazione </li>';
                                    }
                                }
                        
                            $this->creaFeedbackUtente($msg, $vd, "Prenotazione eliminata");
                            }
                        }
                        $viaggi = ViaggioFactory::instance()->getViaggiPrenotatiPerUtente($user);
                        $this->showHomeUtente($vd);
                        
                        
                        break;
                    default : $this->showLoginPage($vd);
                }
            } else {
                // nessun comando
                $user = UserFactory::instance()->cercaUserPerId(
                                $_SESSION[BaseController::user], $_SESSION[BaseController::role]);
                $this->showHomeUtente($vd);
            }
        }

        // includo la vista
        require basename(__DIR__) . '/../view/master.php';
    }

    private function getViaggioPerIndice(&$viaggi, &$request, &$msg) {
        if (isset($request['viaggio'])) {
            // indice per il viaggio definito, verifichiamo che sia un intero
            $intVal = filter_var($request['viaggio'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            if (isset($intVal) && $intVal > -1 && $intVal < count($viaggi)) {
                return $viaggi[$intVal];
            } else {
                $msg[] = "<li> Il viaggio specificato non esiste </li>";
                return null;
            }
        } else {
            $msg[] = '<li>Viaggio non specificato<li>';
            return null;
        }
    }
    
}

?>
