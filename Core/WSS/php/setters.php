<?php
/**
 * Created by PhpStorm.
 * User: stest
 * Date: 16/07/2016
 * Time: 19:00
 *
 * Questa classe contiene tutte le funzioni di tipo Setter, invocate quindi quando il metodo è di tipo POST
 */

class setters {
    protected $modelDbConn;

    function __construct(){
        include("inc/settings.php");
        $this->modelDbConn = new mysqli($db_model_server,$db_model_username,$db_model_password,$db_model_db);
    }

    /*
     * questa funzione inserisce un nuovo raspberry all'interno del database, effettua una verifica se il raspberry
     * non è gia esistente e ritorna l'id del raspberry creato, nel caso vi sia un problema ritorna -1
     *
     * Arguments:
     *  - name = nome del raspberry (univoco)
     *  - description = descrizione del raspberry
     *  - position = posizione del raspberry
     */
    function addRaspi($args){
        $return = -1;
        if(isset($args["name"])){
            $query = "insert into raspberry (name,";
            $queryFin = " values ('".$args["name"]."',";
            if(isset($args["description"])){
                $query .="description,";
                $queryFin .= " '".$args["description"]."',";
            }
            if(isset($args["position"])){
                $query .="position,";
                $queryFin .=" '".$args["position"]."',";
            }
            if(isset($args["tower"])){
                if(is_numeric($args["tower"])){
                    $query .="tower,";
                    $queryFin .=" '".$args["tower"]."',";
                }
            }
            if(isset($args["instId"])){
                if(is_numeric($args["instId"])){
                    $query .="instId,";
                    $queryFin .=" '".$args["instId"]."',";
                }
            }
            if(isset($args["color"])){
                $query .="color,";
                $queryFin .=" '".$args["color"]."',";
            }
            $query .="enable) ";
            $query .= $queryFin;
            $query .="1)";
            if($this->modelDbConn->query($query)){
                $querySearchId = "select r.id from raspberry r where r.name='".$args["name"]."'";
                if($resSearch = $this->modelDbConn->query($querySearchId)){
                    $row = $resSearch->fetch_assoc();
                    $return = $row["id"];
                }

            }
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione aggiunge un sensore ad un raspberry, è necessario fornire la tipologia di sensore, il raspberry su cui è montato
     * e il range dei valori richiesti, il datodi ritorno è l'id assegnato, verra rilasciato -1 in caso di errore
     *
     * Arguments:
     *  - raspId = id del raspberry
     *  - sensType = selettore della tipologia di sensore
     *  - description = descrizione del sensore
     *  - from = valore minimo dello stato di funzionamento normale
     *  - to = valore masismo dello stato di funzionamento normale
     *
     */
    function addSensor($args){
        $return = -1;
        if(isset($args["raspId"]) && isset($args["sensType"])){
            if(is_numeric($args["raspId"]) && is_numeric($args["sensType"])){
                $checkRaspId = "select * from raspberry where id=".$args["raspId"];
                if($resCheck = $this->modelDbConn->query($checkRaspId)){
                    if($resCheck->num_rows !=0){
                        $insSensorQuery = "insert into sensors (type";
                        $insSensorQueryFin = " values (".$args["sensType"];

                        if(isset($args["description"])){
                            $insSensorQuery .=",description";
                            $insSensorQueryFin .= ", '".$args["description"]."'";
                        }
                        if(isset($args["from"])){
                            $insSensorQuery .=",fromValue";
                            $insSensorQueryFin .=", ".$args["from"];
                        }
                        if(isset($args["to"])){
                            $insSensorQuery .=",toValue";
                            $insSensorQueryFin .= ", ".$args["to"];
                        }
                        $insSensorQuery .= ") ".$insSensorQueryFin;
                        $insSensorQuery .=")";
                        if($this->modelDbConn->query($insSensorQuery)){
                            $sensId = $this->modelDbConn->insert_id;
                            $insSensOnRaspi = "insert into sensorOnRaspi (sensID,raspId) values(".$sensId.",".$args["raspId"].")";
                            if($this->modelDbConn->query($insSensOnRaspi)){
                                $return = $sensId;
                            }
                        }
                    }
                }
            }
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione aggiunge un dato rilevato da un sensore e ritorna l'id dell'inserimento oppure -1 nel caso in cui ci sia un errore
     *
     * Arguments:
     *  - id = id del sensore su cui aggiungere il dato
     *  - value = valore rilevato
     */
    function addData($args){
        $return = -1;
        if(isset($args["id"]) && isset($args["value"])){
            if(is_numeric($args["id"]) && is_numeric($args["value"])){
                $insData = "insert into sensorData (sensorId,value,date) ".
                        "values(".$args["id"].",".$args["value"].",NOW())";
                if($this->modelDbConn->query($insData)){
                    $return = $this->modelDbConn->insert_id;
                }
            }
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione permette di modificare un raspberry precedentemente inserito,
     *
     * Arguments:
     *  - id = id raspberry
     *  - name = nome raspberry
     *  - description = descrizione raspberry
     *  - position = posizione raspberry
     *  - enable = flag per abilitare o disabilitare il raspberry
     */
    function editRaspi($args){
        $return = -1;
        if(isset($args["id"])) {
            if(is_numeric($args["id"])){
                $checkRasp = "select * from raspberry r where r.id=".$args["id"];
                if($resCheck = $this->modelDbConn->query($checkRasp)){
                    if($resCheck->num_rows !=0){
                        $check = 0;
                        $updRaspi = "update raspberry set ";
                        if(isset($args["name"])){
                            $check = 1;
                            $updRaspi .="name='".$args["name"]."'";
                        }
                        if(isset($args["description"])){
                            if($check ==0){
                                $check = 1;
                            }else{
                                $updRaspi .=",";
                            }
                            $updRaspi .="description='".$args["description"]."'";
                        }
                        if(isset($args["position"])){
                            if($check ==0){
                                $check = 1;
                            }else{
                                $updRaspi .=",";
                            }
                            $updRaspi .="position='".$args["position"]."'";
                        }
                        if(isset($args["enable"])){
                            if(is_numeric($args["enable"])){
                                if($check ==0){
                                    $check = 1;
                                }else{
                                    $updRaspi .=",";
                                }
                                $updRaspi .= "enable=".$args["enable"];
                            }
                        }
                        if(isset($args["tower"])){
                            if(is_numeric($args["tower"])){
                                if($check ==0){
                                    $check = 1;
                                }else{
                                    $updRaspi .=",";
                                }
                                $updRaspi .= "tower=".$args["tower"];
                            }
                        }
                        if(isset($args["color"])){
                            if($check ==0){
                                $check = 1;
                            }else{
                                $updRaspi .=",";
                            }
                            $updRaspi .="color='".$args["color"]."'";
                        }
                        if($check == 1){
                            $updRaspi .=" where id=".$args["id"];
                            if($this->modelDbConn->query($updRaspi)){
                                $return = 1;
                            }
                        }
                    }
                }
            }
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione serve ad aggiornare un sensore del quale bisogna indicare l'id
     */
    function editSensor($args){
        $return = -1;
        if(isset($args["id"])) {
            if(is_numeric($args["id"])){
                $checkSens = "select * from sensors s where s.id=".$args["id"];
                if($resCheck = $this->modelDbConn->query($checkSens)){
                    if($resCheck->num_rows !=0){
                        $check = 0;
                        $updSens = "update sensors s set ";
                        if(isset($args["description"])) {
                            $check = 1;
                            $updSens .= "s.description='" . $args["description"] . "'";
                        }
                        if(isset($args["type"])){
                            if(is_numeric($args["type"])){
                                if($check ==0){
                                    $check = 1;
                                }else{
                                    $updSens .=",";
                                }
                                $updSens = "s.type=".$args["type"];
                            }
                        }
                        if(isset($args["from"])){
                            if(is_numeric($args["from"])){
                                if($check ==0){
                                    $check = 1;
                                }else{
                                    $updSens .=",";
                                }
                                $updSens = "s.fromValue=".$args["from"];
                            }
                        }
                        if(isset($args["to"])){
                            if(is_numeric($args["to"])){
                                if($check ==0){
                                    $check = 1;
                                }else{
                                    $updSens .=",";
                                }
                                $updSens = "s.toValue=".$args["to"];
                            }
                        }
                        if($check ==1){
                            if($this->modelDbConn->query($updSens)){
                                $return = 1;
                            }
                        }
                    }
                }
            }
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione permette di spostare un sensore da un raspberry ad un altro
     */
    function moveSensor($args){
        $return = -1;
        if(isset($args["sensId"]) && isset($args["raspId"])){
            if(is_numeric($args["sensId"]) && is_numeric($args["sensId"])){
                $checkSensId = "select * from sensorOnRaspi where sensId=".$args["sensId"];

                if($resCheck = $this->modelDbConn->query($checkSensId)){
                    if($resCheck->num_rows ==1){
                        $updSensRasp = "update sensorOnRaspi set raspId=".$args["raspId"]." where sensId=".$args["sensId"];
                        if($this->modelDbConn->query(($updSensRasp))){
                            $return = 1;
                        }
                    }
                }
            }
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * Questa funziona imposta la configurazione di una determinata installazione
     */
    function setEnvironment($args){
        $return = -1;
        if(isset($args["instId"])){
            if(is_numeric($args["instId"])){
                $query = "select * from configuration where instId=".$args["instId"];
                if($resQuery = $this->modelDbConn->query($query)){
                    if($resQuery->num_rows >0){
                        //update
                        $updconf = "update configuration c set ";
                        if(isset($args["apiKey"])){
                            $check = 1;
                            $updconf .="c.apiKey='".$args["apiKey"]."'";
                        }
                        if(isset($args["folder"])){
                            if($check ==0){
                                $check = 1;
                            }else{
                                $updconf .=",";
                            }
                            $updconf .= "c.folder='".$args["folder"]."'";
                        }
                        if(isset($args["twitterConsumerKey"])){
                            if($check ==0){
                                $check = 1;
                            }else{
                                $updconf .=",";
                            }
                            $updconf .= "c.twitterConsumerKey='".$args["twitterConsumerKey"]."'";
                        }
                        if(isset($args["twitterConsumerSecret"])){
                            if($check ==0){
                                $check = 1;
                            }else{
                                $updconf .=",";
                            }
                            $updconf .= "c.twitterConsumerSecret='".$args["twitterConsumerSecret"]."'";
                        }
                        if(isset($args["twitterAccessToken"])){
                            if($check ==0){
                                $check = 1;
                            }else{
                                $updconf .=",";
                            }
                            $updconf .= "c.twitterAccessToken='".$args["twitterAccessToken"]."'";
                        }
                        if(isset($args["twitterAccessTokenSecret"])){
                            if($check ==0){
                                $check = 1;
                            }else{
                                $updconf .=",";
                            }
                            $updconf .= "c.twitterAccessTokenSecret='".$args["twitterAccessTokenSecret"]."'";
                        }
                        if(isset($args["twitterContactName"])){
                            if($check ==0){
                                $check = 1;
                            }else{
                                $updconf .=",";
                            }
                            $updconf .= "c.twitterContactName='".$args["twitterContactName"]."'";
                        }
                        if($check ==1){
                            if($this->modelDbConn->query($updconf." where c.id=".$args["instId"])){
                                $return = 1;
                            }
                        }
                    }else{
                        $query = "insert into configuration (";
                        $queryFin = " values (";
                        if(isset($args["apiKey"])){
                            $query .="apiKey,";
                            $queryFin .= " '".$args["apiKey"]."',";
                        }
                        if(isset($args["instId"])){
                            $query .="instId,";
                            $queryFin .= " '".$args["instId"]."',";
                        }
                        if(isset($args["folder"])){
                            $query .="folder,";
                            $queryFin .=" '".$args["folder"]."',";
                        }
                        if(isset($args["twitterConsumerKey"])){
                            $query .="twitterConsumerKey,";
                            $queryFin .=" '".$args["twitterConsumerKey"]."',";
                        }
                        if(isset($args["twitterConsumerSecret"])){
                            $query .="twitterConsumerSecret,";
                            $queryFin .=" '".$args["twitterConsumerSecret"]."',";
                        }
                        if(isset($args["twitterAccessToken"])){
                            $query .="twitterAccessToken,";
                            $queryFin .=" '".$args["twitterAccessToken"]."',";
                        }
                        if(isset($args["twitterAccessTokenSecret"])){
                            $query .="twitterAccessTokenSecret,";
                            $queryFin .=" '".$args["twitterAccessTokenSecret"]."',";
                        }
                        if(isset($args["twitterContactName"])){
                            $query .="twitterContactName,";
                            $queryFin .=" '".$args["twitterContactName"]."',";
                        }
                        $query .= ") ".$queryFin;
                        $query .=")";

                        if($this->modelDbConn->query($query)){
                            $return = 1;
                        }

                    }
                }
            }
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }
}