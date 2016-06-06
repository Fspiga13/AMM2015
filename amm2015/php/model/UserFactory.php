<?php

include_once 'User.php';
include_once 'Organizzatore.php';
include_once 'Utente.php';

/**
 * Classe per la creazione degli utenti del sistema
 *
 */
class UserFactory {

    private static $singleton;

    private function __constructor() {
        
    }

    /**
     * Restiuisce un singleton per creare utenti
     * @return \UserFactory
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new UserFactory();
        }

        return self::$singleton;
    }

    /**
     * Carica uno user tramite username e password
     * @param string $username
     * @param string $password
     * @return \User|\Organizzatore|\Utente
     */
    public function caricaUser($username, $password) {


        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[loadUser] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // cerco nella tabella degli users
        $query = "select users.id_user users_id,
            users.tipo users_tipo,
            users.nome users_nome,
            users.cognome users_cognome,
            users.email users_email,
            users.citta users_citta,
            users.via users_via,
            users.numero_civico users_numero_civico,
            users.username users_username,
            users.password users_password
            
            from users 
            where users.username = ? and users.password = ?";
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[loadUser] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('ss', $username, $password)) {
            error_log("[loadUser] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $user = self::caricaUserDaStmt($stmt);
        if (isset($user)) {
            // ho trovato uno user
            $mysqli->close();
            return $user;
        }
    }

    /**
     * Restituisce tutti gli utenti prenotati ad un dato viaggio
     * @param $viaggio_id identificatore del viaggio in cui cercare gli utenti prenotati
     * @return array
     */
    public function getUtentiPrenotatiPerViaggio($viaggio_id) {
        $utenti = array();
        $query = "select 
                users.id_user users_id,
                users.tipo users_tipo,
                users.nome users_nome,
                users.cognome users_cognome,
                users.email users_email,
                users.citta users_citta,
                users.via users_via,
                users.numero_civico users_numero_civico,
                users.username users_username,
                users.password users_password

                from users 
                join prenotazioni on prenotazioni.id_user = users.id_user
                where users.tipo= 'Utente'
                and prenotazioni.id_viaggio = ?";

       $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getViaggiPerUtente] impossibile inizializzare il database");
            $mysqli->close();
            return $utenti;
        }
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getViaggiPerUtente] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return $utenti;
        }
        
        if (!$stmt->bind_param('i', $viaggio_id)) {
            error_log("[getUtentiPrenotatiPerViaggio] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $utenti = self::caricaUsersDaStmt($stmt);

        return $utenti;
    }

    /**
     * Cerca uno user per id
     * @param int $id
     * @return User un oggetto User nel caso sia stato trovato,
     * NULL altrimenti
     */
    public function cercaUserPerId($id, $role) {
        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) {
            return null;
        }
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[cercaUserPerId] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        $query = "select 
        users.id_user users_id,
        users.tipo users_tipo,
        users.nome users_nome,
        users.cognome users_cognome,
        users.email users_email,
        users.citta users_citta,
        users.via users_via,
        users.numero_civico users_numero_civico,
        users.username users_username,
        users.password users_password

        from users 
        where users.id_user = ?
        and users.tipo = ?";
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[cercaUserPerId] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        switch($role){
            case User::Organizzatore:
                $ruolo='Organizzatore';
                break;
            case User::Utente:
                $ruolo = 'Utente';
                break;
            default:break; 
            
        }
        

        if (!$stmt->bind_param('is', $intval, $ruolo)) {
            error_log("[cercaUserPerId] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $toRet =  self::caricaUserDaStmt($stmt);
        $mysqli->close();
        return $toRet;
        
    }

    /**
     * Crea uno utente da una riga del db
     * @param type $row
     * @return \Utente
     */
    public function creaUtenteDaArray($row) {
        $utente = new Utente();
        $utente->setId($row['users_id']);
        $utente->setNome($row['users_nome']);
        $utente->setCognome($row['users_cognome']);
        $utente->setCitta($row['users_citta']);
        $utente->setVia($row['users_via']);
        $utente->setEmail($row['users_email']);
        $utente->setNumeroCivico($row['users_numero_civico']);
        $utente->setRuolo(User::Utente);
        $utente->setUsername($row['users_username']);
        $utente->setPassword($row['users_password']);
        return $utente;
    }

    /**
     * Crea un organizzatore da una riga del db
     * @param type $row
     * @return \Organizzatore
     */
    public function creaOrganizzatoreDaArray($row) {
        $organizzatore = new Organizzatore();
        $organizzatore->setId($row['users_id']);
        $organizzatore->setNome($row['users_nome']);
        $organizzatore->setCognome($row['users_cognome']);
        $organizzatore->setEmail($row['users_email']);
        $organizzatore->setCitta($row['users_citta']);
        $organizzatore->setVia($row['users_via']);
        $organizzatore->setNumeroCivico($row['users_numero_civico']);
        $organizzatore->setRuolo(User::Organizzatore);
        $organizzatore->setUsername($row['users_username']);
        $organizzatore->setPassword($row['users_password']);

        return $organizzatore;
    }

    /**
     * Salva i dati relativi ad un utente sul db
     * @param User $user
     * @return il numero di righe modificate
     */
    public function salva(User $user) {
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[salva] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();
        $count = 0;
        
        $count = $this->salvaUser($user, $stmt);

        $stmt->close();
        $mysqli->close();
        return $count;
    }

    /**
     * Rende persistenti le modifiche all'anagrafica di uno user sul db
     * @param User $s lo user considerato
     * @param mysqli_stmt $stmt un prepared statement
     * @return int il numero di righe modificate
     */
    private function salvaUser(User $u, mysqli_stmt $stmt) {
        $query = " update users set 
                    password = ?,
                    nome = ?,
                    cognome = ?,
                    email = ?,
                    numero_civico = ?,
                    citta = ?,
                    via = ?
                    where users.id_user = ?
                    ";
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[salvaUser] impossibile" .
                    " inizializzare il prepared statement");
            return 0;
        }

        if (!$stmt->bind_param('ssssissi', 
                $u->getPassword(), 
                $u->getNome(), 
                $u->getCognome(), 
                $u->getEmail(), 
                $u->getNumeroCivico(), 
                $u->getCitta(), 
                $u->getVia(), 
                $u->getId())) {
            error_log("[salvaUser] impossibile" .
                    " effettuare il binding in input");
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[salvaUser] impossibile" .
                    " eseguire lo statement");
            return 0;
        }

        return $stmt->affected_rows;
    }
    
    /**
     * Carica un user eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    private function caricaUserDaStmt(mysqli_stmt $stmt) {

        if (!$stmt->execute()) {
            error_log("[caricaUserDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['users_id'],
                $row['users_tipo'],
                $row['users_nome'], 
                $row['users_cognome'], 
                $row['users_email'], 
                $row['users_citta'],
                $row['users_via'],
                $row['users_numero_civico'],
                $row['users_username'], 
                $row['users_password']); 
        if (!$bind) {
            error_log("[caricaUserDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        if ($row['users_tipo'] == 'Utente') {
            return self::creaUtenteDaArray($row);
        } else {
            return self::creaOrganizzatoreDaArray($row);
        }
    }
    
      /**
     * Carica piu user eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    private function caricaUsersDaStmt(mysqli_stmt $stmt) {
        $utenti = array();
        if (!$stmt->execute()) {
            error_log("[caricaUsersDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['users_id'],
                $row['users_tipo'],
                $row['users_nome'], 
                $row['users_cognome'], 
                $row['users_email'], 
                $row['users_citta'],
                $row['users_via'],
                $row['users_numero_civico'],
                $row['users_username'], 
                $row['users_password']); 
        if (!$bind) {
            error_log("[caricaUsersDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            if ($row['users_tipo'] == 'Utente') {
            $utenti[] = self::creaUtenteDaArray($row);
            } else {
            $utenti[] = self::creaOrganizzatoreDaArray($row);
            }
        }
        
        $stmt->close();
        
        return $utenti;
    }
    
}
?>