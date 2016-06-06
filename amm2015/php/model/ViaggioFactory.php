<?php

include_once 'Viaggio.php';
include_once 'UserFactory.php';
include_once 'SedeFactory.php';
include_once 'Utente.php';
include_once 'Organizzatore.php';
include_once 'User.php';

/**
 * Classe per creare oggetti di tipo Viaggio
 *
 */
class ViaggioFactory {

    private static $singleton;
    
    private function __constructor(){
    }
    
    /**
     * Restiuisce un singleton per creare viaggi
     * @return \ViaggioFactory
     */
    public static function instance(){
        if(!isset(self::$singleton)){
            self::$singleton = new ViaggioFactory();
        }
        
        return self::$singleton;
    }
    
    public function cercaViaggioPerId($viaggioid){
        $viaggi = array();
        $query = "select 
               viaggi.id_viaggio viaggi_id,
               viaggi.data_partenza viaggi_data_partenza,
               viaggi.data_ritorno viaggi_data_ritorno,
               viaggi.posti viaggi_capienza,
               viaggi.prezzo viaggi_prezzo,
               viaggi.id_organizzatore viaggi_organizzatore,
               
               sedi.id_sede sedi_id,
               sedi.nazione sedi_nazione,
               sedi.citta sedi_citta,
               
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

               from viaggi
               join sedi on viaggi.id_sede = sedi.id_sede
               join users on users.id_user = viaggi.id_organizzatore
               where viaggi.id_viaggio = ?";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[cercaViaggioPerId] impossibile inizializzare il database");
            $mysqli->close();
            return $viaggi;
        }
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[cercaViaggioPerId] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return $viaggi;
        }

        
        if (!$stmt->bind_param('i', $viaggioid)) {
            error_log("[cercaViaggioPerId] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return $viaggi;
        }

        $viaggi =  self::caricaViaggiDaStmt($stmt);
        foreach($viaggi as $viaggio){
            self::caricaPrenotati($viaggio);
        }
        if(count($viaggi > 0)){
            $mysqli->close();
            return $viaggi[0];
        }else{
            $mysqli->close();
            return null;
        }
    }
    
      /**
     * Restituisce tutti i viaggi a cui un utente si puo prenotare
     * @param Utente $utente l utente per la ricerca
     * @return array una lista di viaggi (riferimento)
     */
    public function &getViaggiPrenotabiliPerUtente(Utente $utente) {
        $viaggi = array();
        $query = "select 
               viaggi.id_viaggio viaggi_id,
               viaggi.data_partenza viaggi_data_partenza,
               viaggi.data_ritorno viaggi_data_ritorno,
               viaggi.posti viaggi_capienza,
               viaggi.prezzo viaggi_prezzo,
               viaggi.id_organizzatore viaggi_organizzatore,
               
               sedi.id_sede sedi_id,
               sedi.nazione sedi_nazione,
               sedi.citta sedi_citta,
               
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

               from viaggi
               join sedi on viaggi.id_sede = sedi.id_sede
               join users on users.id_user = viaggi.id_organizzatore
               where viaggi.data_partenza > current_date
               and viaggi.id_viaggio not in(
                    select prenotazioni.id_viaggio
                    from prenotazioni
                    where id_user = ?)";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getViaggiPerUtente] impossibile inizializzare il database");
            $mysqli->close();
            return $viaggi;
        }
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getViaggiPerUtente] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return $viaggi;
        }
                
        if (!$stmt->bind_param('i', $utente->getId())) {
            error_log("[getViaggiPrenotatiPerUtente] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $viaggi =  self::caricaViaggiDaStmt($stmt);
        foreach($viaggi as $viaggio){
            self::caricaPrenotati($viaggio);
        }
        $mysqli->close();
        return $viaggi;
    }

    /**
     * Restituisce tutti i viaggi inseriti da un dato Organizzatore
     * @param Organizzatore $organizzatore l organizzatore per la ricerca
     * @return array una lista di viaggi (riferimento)
     */
    public function &getViaggiPerOrganizzatore(Organizzatore $organizzatore) {
        $viaggi = array();
        $query = "select 
               viaggi.id_viaggio viaggi_id,
               viaggi.data_partenza viaggi_data_partenza,
               viaggi.data_ritorno viaggi_data_ritorno,
               viaggi.posti viaggi_capienza,
               viaggi.prezzo viaggi_prezzo,
               viaggi.id_organizzatore viaggi_organizzatore,
               
               sedi.id_sede sedi_id,
               sedi.nazione sedi_nazione,
               sedi.citta sedi_citta,
               
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
               
               from viaggi
               join sedi on viaggi.id_sede = sedi.id_sede
               join users on viaggi.id_organizzatore = users.id_user
               where users.id_user = ? and users.tipo = 'Organizzatore'";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getViaggiPerUtente] impossibile inizializzare il database");
            $mysqli->close();
            return $viaggi;
        }
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getViaggiPerUtente] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return $viaggi;
        }
        
        if (!$stmt->bind_param('i', $organizzatore->getId())) {
            error_log("[getViaggioPerUtente] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return $viaggi;
        }

        $viaggi =  self::caricaViaggiDaStmt($stmt);
        foreach($viaggi as $viaggio){
            self::caricaPrenotati($viaggio);
        }
        $mysqli->close();
        return $viaggi;
    }
    
    private function &caricaViaggiDaStmt(mysqli_stmt $stmt){
        $viaggi = array();
         if (!$stmt->execute()) {
            error_log("[caricaViaggiDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['viaggi_id'],
                $row['viaggi_data_partenza'],
                $row['viaggi_data_ritorno'],
                $row['viaggi_capienza'],
                $row['viaggi_prezzo'],
                $row['viaggi_organizzatore'],
                $row['sedi_id'], 
                $row['sedi_nazione'], 
                $row['sedi_citta'],
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
            error_log("[caricaViaggiDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $viaggi[] = self::creaDaArray($row);
        }
        
        $stmt->close();
        
        return $viaggi;
    }

    public function creaDaArray($row){
        $viaggio = new Viaggio();
        $viaggio->setId($row['viaggi_id']);
        $viaggio->setCapienza($row['viaggi_capienza']);
        $viaggio->setDataPartenza(new DateTime($row['viaggi_data_partenza']));
        $viaggio->setDataRitorno(new DateTime($row['viaggi_data_ritorno']));
        $viaggio->setSede(SedeFactory::instance()->creaDaArray($row));
        $viaggio->setOrganizzatore(UserFactory::instance()->creaOrganizzatoreDaArray($row));
        $viaggio->setPrezzo($row['viaggi_prezzo']);
        return $viaggio;
    }

    public function caricaPrenotati(Viaggio $viaggio){
        
        $query = "select 
            prenotazioni.id_user  users_id

            from prenotazioni
            where prenotazioni.id_viaggio = ?";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[caricaPrenotati impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[caricaPrenotati] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('i', $viaggio->getId())) {
            error_log("[caricaPrenotati] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }
        
        if (!$stmt->execute()) {
            error_log("[caricaPrenotati] impossibile" .
                    " eseguire lo statement");
            $mysqli->close();
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['users_id']);
        if (!$bind) {
            error_log("[caricaPrenotati] impossibile" .
                    " effettuare il binding in output");
            $mysqli->close();
            return null;
        }

        while ($stmt->fetch()) {
            $viaggio->prenota($row['users_id']);
        }
        
        $mysqli->close();
        $stmt->close();
        
    }
    
    public function aggiungiPrenotazione(Utente $u, Viaggio $v){
        $query = "insert into prenotazioni (id_user, id_viaggio) values (?, ?)";
        return $this->queryPrenotazione($u, $v, $query);
    }
   
    public function cancellaPrenotazione(Utente $u, Viaggio $v){
        $query = "delete from prenotazioni where id_user = ? and id_viaggio = ?";
        return $this->queryPrenotazione($u, $v, $query);
    }
    
    private function queryPrenotazione(Utente $u, Viaggio $v, $query){
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[aggiungiPrenotazione] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[aggiungiPrenotazione] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }
        
        if (!$stmt->bind_param("ii", $u->getId(), $v->getId())) {
            error_log("[aggiungiPrenotazione] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[aggiungiPrenotazione] impossibile" .
                    " eseguire lo statement");
            $mysqli->close();
            return 0;
        }
        $mysqli->close();
        return $stmt->affected_rows;
    }
    
    public function salva(Viaggio $viaggio){
         $query = "update viaggi set 
                    data_partenza = ?,
                    data_ritorno = ?,
                    prezzo = ?,
                    posti = ?,
                    id_organizzatore = ?,
                    id_sede= ?
                    where viaggi.id_viaggio = ?";
        return $this->modificaDB($viaggio, $query);
        
    }
    
    public function nuovo(Viaggio $viaggio){
        $query = "insert into viaggi (data_partenza, data_ritorno,
				  prezzo, posti, id_organizzatore, id_sede, id_viaggio)
                  values (?, ?, ?, ?, ?, ?, ?)";
        return $this->modificaDB($viaggio, $query);
    }
    
    public function cancella(Viaggio $viaggio){
        $query = "delete from viaggi where id_viaggio = ?";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[cancellaViaggio] impossibile inizializzare il database");
            return 0;
        }

        $stmt = $mysqli->stmt_init();
       
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[cancellaViaggio] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->bind_param('i',
                $viaggio->getId())) {
            error_log("[cancellaViaggio] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[cancellaViaggio] impossibile" .
                    " eseguire lo statement");
            $mysqli->close();
            return 0;
        }

        $mysqli->close();
        return $stmt->affected_rows;
    }
    
    private function modificaDB(Viaggio $viaggio, $query){
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[salva] impossibile inizializzare il database");
            return 0;
        }

        $stmt = $mysqli->stmt_init();
       
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[modificaDB] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->bind_param('ssiiiii', 
                $viaggio->getDataPartenza()->format('Y-m-d'),
                $viaggio->getDataRitorno()->format('Y-m-d'),
                $viaggio->getPrezzo(),
                $viaggio->getCapienza(),
                $viaggio->getOrganizzatore()->getId(),
                $viaggio->getSede()->getId(),
                $viaggio->getId())) {
            error_log("[modificaDB] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[modificaDB] impossibile" .
                    " eseguire lo statement");
            $mysqli->close();
            return 0;
        }

        $mysqli->close();
        return $stmt->affected_rows;
    }
    
    /**
     * Restituisce tutti i viaggi a cui un utente e' prenotato
     * @param Utente $utente l utente per la ricerca
     * @return array una lista di viaggi (riferimento)
     */
    public function getViaggiPrenotatiPerUtente(Utente $utente){
        $viaggi = array();
        $query = "select 
               viaggi.id_viaggio viaggi_id,
               viaggi.data_partenza viaggi_data_partenza,
               viaggi.data_ritorno viaggi_data_ritorno,
               viaggi.posti viaggi_capienza,
               viaggi.prezzo viaggi_prezzo,
               viaggi.id_organizzatore viaggi_organizzatore,
               
               sedi.id_sede sedi_id,
               sedi.nazione sedi_nazione,
               sedi.citta sedi_citta,
               
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
               
               from prenotazioni
               join viaggi on prenotazioni.id_viaggio = viaggi.id_viaggio
               join sedi on viaggi.id_sede = sedi.id_sede
               join users on prenotazioni.id_user = users.id_user
               where prenotazioni.id_user = ?";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getViaggiPrenotatiPerUtente] impossibile inizializzare il database");
            $mysqli->close();
            return $viaggi;
        }
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getViaggiPrenotatiPerUtente] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return $viaggi;
        }
        
        if (!$stmt->bind_param('i', $utente->getId())) {
            error_log("[getViaggiPrenotatiPerUtente] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $viagi =  self::caricaViaggiDaStmt($stmt);
        foreach($viagi as $viaggio){
            self::caricaPrenotati($viaggio);
        }
        $mysqli->close();
        return $viagi;
        
    }
    
    /**
     * Restituisce tutti i viaggi
     * @return array una lista di viaggi (riferimento)
     */
    public function getViaggi(){
        $viaggi = array();
        $query = "select 
               viaggi.id_viaggio viaggi_id,
               viaggi.data_partenza viaggi_data_partenza,
               viaggi.data_ritorno viaggi_data_ritorno,
               viaggi.posti viaggi_capienza,
               viaggi.prezzo viaggi_prezzo,
               viaggi.id_organizzatore viaggi_organizzatore,
               
               sedi.id_sede sedi_id,
               sedi.nazione sedi_nazione,
               sedi.citta sedi_citta,
               
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
               
               from viaggi 
               join sedi on viaggi.id_sede = sedi.id_sede
               join users on viaggi.id_organizzatore = users.id_user";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getViaggi] impossibile inizializzare il database");
            $mysqli->close();
            return $viaggi;
        }
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getViaggi] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return $viaggi;
        }
        
        $viaggi =  self::caricaViaggiDaStmt($stmt);
        foreach($viaggi as $viaggio){
            self::caricaPrenotati($viaggio);
        }
        $mysqli->close();
        return $viaggi;
        
    }
}

?>
