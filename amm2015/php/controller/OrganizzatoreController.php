<?php

include_once 'BaseController.php';
include_once basename(__DIR__) . '/../model/ViaggioFactory.php';
include_once basename(__DIR__) . '/../model/UserFactory.php';

/**
 * 
 * Controller che gestisce la modifica dei dati dell'applicazione relativa agli 
 * Organizzatori da parte di utenti con ruolo Organizzatore
 *
 */
class OrganizzatoreController extends BaseController {

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

        // imposto il token per impersonare un utente (nel caso lo stia facendo)
        $this->setImpToken($vd, $request);

        if (!$this->loggedIn()) {
            // utente non autenticato, rimando alla home
            $this->showLoginPage($vd);
        } else {
            // utente autenticato
            $user = UserFactory::instance()->cercaUserPerId(
                    $_SESSION[BaseController::user], $_SESSION[BaseController::role]);

            // verifico quale sia la sottopagina della categoria
            // Organizzatore da servire ed imposto il descrittore 
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

                    // inserimento di una lista di viaggi
                    case 'viaggi':
                        $viaggi = ViaggioFactory::instance()->getViaggiPerOrganizzatore($user);
                        $vd->setSottoPagina('viaggi');
                        break;

                    // modifica di un viaggio
                    case 'viaggi_modifica':
                        $msg = array();
                        $viaggi = ViaggioFactory::instance()->getViaggiPerOrganizzatore($user);
                        $sedi = SedeFactory::instance()->getSedi();
                        $mod_viaggio = $this->getViaggio($request, $msg);
                        if (!isset($mod_viaggio)) {
                            $vd->setSottoPagina('viaggi');
                        } else {
                            $vd->setSottoPagina('viaggi_modifica');
                        }
                        break;

                    // creazione di un viaggio
                    case 'viaggi_crea':
                        $msg = array();
                        $viaggi = ViaggioFactory::instance()->getViaggiPerOrganizzatore($user);
                        $sedi = SedeFactory::instance()->getSedi();
                        if (!isset($request['cmd'])) {
                            $vd->setSottoPagina('viaggi');
                        } else {
                            $vd->setSottoPagina('viaggi_crea');
                        }

                        break;

                    // visualizzazione della lista di prenotati ad un viaggio
                    case 'viaggi_prenotazione':
                        $msg = array();
                        $viaggi = ViaggioFactory::instance()->getViaggiPerOrganizzatore($user);
                        $mod_viaggio = $this->getViaggio($request, $msg);
                        $prenotati = UserFactory::instance()->getUtentiPrenotatiPerViaggio($mod_viaggio->getId());
                        if (!isset($mod_viaggio)) {
                            $vd->setSottoPagina('viaggi');
                        } else {
                            $vd->setSottoPagina('viaggi_prenotazione');
                        }
                        break;

                    default:
                        $vd->setSottoPagina('home');
                        break;
                }
            }


            // gestione dei comandi inviati dall'organizzatore
            if (isset($request["cmd"])) {

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
                        $this->showHomeOrganizzatore($vd);
                        break;

                    // cambio email
                    case 'email':
                        // in questo array inserisco i messaggi di 
                        // cio' che non viene validato
                        $msg = array();
                        $this->aggiornaEmail($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Email aggiornata");
                        $this->showHomeOrganizzatore($vd);
                        break;

                    // modifica della password
                    case 'password':
                        $msg = array();
                        $this->aggiornaPassword($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Password aggiornata");
                        $this->showHomeOrganizzatore($vd);
                        break;

                    // richiesta modifica di un viaggio esistente,
                    // dobbiamo mostrare le informazioni
                    case 'v_modifica':
                        $viaggi = ViaggioFactory::instance()->getViaggiPerOrganizzatore($user);
                        if (isset($request['viaggio'])) {
                            $intVal = filter_var($request['viaggio'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($intVal)) {
                                $mod_viaggio = $this->cercaViaggioPerId($intVal, $viaggi);
                                $vd->setStato('v_modifica');
                            }
                        }
                        $this->showHomeOrganizzatore($vd);
                        break;

                    // salvataggio delle modifiche ad un viaggio esistente
                    case 'v_salva':
                        $msg = array();
                        if (isset($request['viaggio'])) {
                            $intVal = filter_var($request['viaggio'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($intVal)) {
                                $mod_viaggio = $this->cercaViaggioPerId($intVal, $viaggi);
                                $this->updateViaggio($mod_viaggio, $request, $msg);
                                if (count($msg) == 0 && ViaggioFactory::instance()->salva($mod_viaggio) != 1) {
                                    $msg[] = '<li> Impossibile salvare il viaggio </li>';
                                }
                                $this->creaFeedbackUtente($msg, $vd, "Viaggio aggiornato");
                                if (count($msg) == 0) {
                                    $vd->setSottoPagina('viaggi');
                                }
                            }
                        } else {
                            $msg[] = '<li> Viaggio non specificato </li>';
                        }
                        $this->showHomeOrganizzatore($vd);
                        break;

                    // l'utente non vuole modificare il viaggio selezionato
                    case 'v_annulla':
                        $vd->setSottoPagina('viaggi');
                        $this->showHomeOrganizzatore($vd);
                        break;

                    // richesta di visualizzazione del form per la creazione di un nuovo
                    // viaggio
                    case 'v_crea':
                        $viaggi = ViaggioFactory::instance()->getViaggiPerOrganizzatore($user);
                        $sedi = SedeFactory::instance()->getSedi();
                        $vd->setSottoPagina('viaggi_crea');
                        $this->showHomeOrganizzatore($vd);
                        break;

                    // creazione di un nuovo viaggio
                    case 'v_nuovo':
                        $msg = array();
                        $viaggi = ViaggioFactory::instance()->getViaggi();
                        $nuovo = new Viaggio();
                        $nuovo->setId($this->prossimoIdViaggi($viaggi));
                        $nuovo->setOrganizzatore($user);
                        $this->updateViaggio($nuovo, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Viaggio creato");
                        if (count($msg) == 0) {
                            $vd->setSottoPagina('viaggi');
                            if (ViaggioFactory::instance()->nuovo($nuovo) != 1) {
                                $msg[] = '<li> Impossibile creare il viaggio </li>';
                            }
                        }
                        $viaggi = ViaggioFactory::instance()->getViaggiPerOrganizzatore($user);
                        $this->showHomeOrganizzatore($vd);
                        break;

                    // mostra la lista dei prenotati
                    case 'v_prenotati':
                        $viaggi = ViaggioFactory::instance()->getViaggiPerOrganizzatore($user);
                        if (isset($request['viaggio'])) {
                            $intVal = filter_var($request['viaggio'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($intVal)) {
                                $mod_viaggio = $this->cercaViaggioPerId($intVal, $viaggi);
                            }
                        }
                        $this->showHomeOrganizzatore($vd);
                        break;

                    // cancella un viaggio
                    case 'v_cancella':
                        if (isset($request['viaggio'])) {
                            $intVal = filter_var($request['viaggio'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($intVal)) {
                                $mod_viaggio = ViaggioFactory::instance()->cercaViaggioPerId($intVal);
                                if ($mod_viaggio != null) {
                                    if (ViaggioFactory::instance()->cancella($mod_viaggio) != 1) {
                                        $msg[] = '<li> Impossibile cancellare il viaggio </li>';
                                    }
                                }

                                $this->creaFeedbackUtente($msg, $vd, "Viaggio eliminato");
                            }
                        }
                        $viaggi = ViaggioFactory::instance()->getViaggiPerOrganizzatore($user);
                        $this->showHomeOrganizzatore($vd);
                        break;

                    // default
                    default:
                        $this->showHomeOrganizzatore($vd);
                        break;
                }
            } else {
                // nessun comando, dobbiamo semplicemente visualizzare 
                // la vista
                // nessun comando
                $user = UserFactory::instance()->cercaUserPerId(
                        $_SESSION[BaseController::user], $_SESSION[BaseController::role]);
                $this->showHomeOrganizzatore($vd);
            }
        }


        // richiamo la vista
        require basename(__DIR__) . '/../view/master.php';
    }

    /**
     * Aggiorna i dati relativi ad un viaggio in base ai parametri specificati
     * dall'organizzatore
     * @param Viaggio $mod_viaggio il viaggio da modificare
     * @param array $request la richiesta da gestire 
     * @param array $msg array dove inserire eventuali messaggi d'errore
     */
    private function updateViaggio($mod_viaggio, &$request, &$msg) {
        if (isset($request['sede'])) {
            $sede = SedeFactory::creaSedeDaId($request['sede']);
            if (isset($sede)) {
                $mod_viaggio->setSede($sede);
            } else {
                $msg[] = "<li>Sede non trovata</li>";
            }
        }
        if (isset($request['data_partenza'])) {
            $data_p = DateTime::createFromFormat("d/m/Y", $request['data_partenza']);
            if (isset($data_p) && $data_p != false) {
                $mod_viaggio->setDataPartenza($data_p);
            } else {
                $msg[] = "<li>La data di partenza specificata non &egrave; corretta</li>";
            }
        }
        if (isset($request['data_ritorno'])) {
            $data_r = DateTime::createFromFormat("d/m/Y", $request['data_ritorno']);
            if (isset($data_r) && $data_r != false) {
                $mod_viaggio->setDataRitorno($data_r);
            } else {
                $msg[] = "<li>La data di rientro specificata non &egrave; corretta</li>";
            }
        }
        if (isset($request['posti'])) {
            if (!$mod_viaggio->setCapienza($request['posti'])) {
                $msg[] = "<li>La capienza specificata non &egrave; corretta</li>";
            }
        }
        
        if (isset($request['prezzo'])) {
            if (!$mod_viaggio->setPrezzo($request['prezzo'])) {
                $msg[] = "<li>Il prezzo specificato non &egrave; corretta</li>";
            }
        }        

    }

    /**
     * Ricerca un viaggio per id all'interno di una lista
     * @param int $id l'id da cercare
     * @param array $viaggi un array di viaggi
     * @return Viaggio il viaggio con l'id specificato se presente nella lista,
     * null altrimenti
     */
    private function cercaViaggioPerId($id, &$viaggi) {
        foreach ($viaggi as $viaggio) {
            if ($viaggio->getId() == $id) {
                return $viaggio;
            }
        }

        return null;
    }

    /**
     * Calcola l'id per un nuovo viaggio
     * @param array $viaggi una lista di viaggi
     * @return int il prossimo id dei viaggi
     */
    private function prossimoIdViaggi(&$viaggi) {
        $max = -1;
        foreach ($viaggi as $a) {
            if ($a->getId() > $max) {
                $max = $a->getId();
            }
        }
        return $max + 1;
    }


    /**
     * Restituisce il viaggio specificato dall'organizzatore tramite una richiesta HTTP
     * @param array $request la richiesta HTTP
     * @param array $msg un array dove inserire eventuali messaggi d'errore
     * @return Viaggio il viaggio selezionato, null se non e' stato trovato
     */
    private function getViaggio(&$request, &$msg) {
        if (isset($request['viaggio'])) {
            $viaggio_id = filter_var($request['viaggio'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $viaggio = ViaggioFactory::instance()->cercaViaggioPerId($viaggio_id);
            if ($viaggio == null) {
                $msg[] = "Il viaggio selezionato non &egrave; corretto</li>";
            }
            return $viaggio;
        } else {
            return null;
        }
    }

}

?>
