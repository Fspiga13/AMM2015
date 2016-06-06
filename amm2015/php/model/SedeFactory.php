<?php

include_once 'Sede.php';
include_once 'Db.php';

/**
 * Classe per creare oggetti di tipo Sede
 *
 */
class SedeFactory {
    
    private static $singleton;
    
    private function __constructor(){
    }
    
    
    /**
     * Restiuisce un singleton per creare Sedi
     * @return \SedeFactory
     */
    public static function instance(){
        if(!isset(self::$singleton)){
            self::$singleton = new SedeFactory();
        }
        
        return self::$singleton;
    }
    
    /**
     * Restituisce la lista di tutte le Sedi
     * @return array|\Sede
     */
    public function &getSedi(){
        
        $dip = array();
        $query = "select 
               sedi.id_sede sedi_id,
               sedi.nazione sedi_nazione,
               sedi.citta sedi_citta
               from sedi";
        $mysqli = Db::getInstance()->connectDb();
        if(!isset($mysqli)){
            error_log("[getSedi] impossibile inizializzare il database");
            $mysqli->close();
            return $dip;
        }
        $result = $mysqli->query($query);
        if($mysqli->errno > 0){
            error_log("[getSedi] impossibile eseguire la query");
            $mysqli->close();
            return $dip;
        }
        
        while($row = $result->fetch_array()){
            $dip[] = self::getSede($row);
        }
        
        $mysqli->close();
        return $dip;
    }
    
    /**
     * Crea una Sede da una riga di DB
     * @param type $row
     */
    public function creaDaArray($row){
        $dip = new Sede();
        $dip->setId($row['sedi_id']);
        $dip->setNazione($row['sedi_nazione']);
        $dip->setCitta($row['sedi_citta']);
        return $dip;
    }
    
    /**
     * Crea un oggetto di tipo Sede a partire da una riga del DB
     * @param type $row
     * @return \Sede
     */
    private function getSede($row){
        $sede = new Sede();
        $sede->setId($row['sedi_id']);
        $sede->setNazione($row['sedi_nazione']);
        $sede->setCitta($row['sedi_citta']);
        return $sede;
    }
    
   public function creaSedeDaId($id) {
        
        $query = "select *
               from sedi
               where sedi.id_sede = ?";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[creaSedeDaId] impossibile inizializzare il database");
            $mysqli->close();
            return $sedi;
        }
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[creaSedeDaId] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('s', $id)) {
            error_log("[creaSedeDaId] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $sedi = self::caricaSediDaStmt($stmt);
        if(count($sedi) > 0){
            $mysqli->close();
            return $sedi[0];
        }else{
            $mysqli->close();
            return null;
        }
    }
       private function &caricaSediDaStmt(mysqli_stmt $stmt){
        $sedi = array();
         if (!$stmt->execute()) {
            error_log("[caricaSediDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['sedi_id'], 
                $row['sedi_nazione'], 
                $row['sedi_citta']);
        if (!$bind) {
            error_log("[caricaSediDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $sedi[] = self::creaDaArray($row);
        }
        
        $stmt->close();
        
        return $sedi;
    }
}

?>
