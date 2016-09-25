<?php
/**
 * Created by PhpStorm.
 * User: stest
 * Date: 16/07/2016
 * Time: 17:22
 *
 * Questa classe contiene tutte le funzioni di tipo Getter, invocate quindi quando il metodo è di tipo GET
 */

class getters {
    protected $modelDbConn;

    function __construct(){
        include("inc/settings.php");
        $this->modelDbConn = new mysqli($db_model_server,$db_model_username,$db_model_password,$db_model_db);
    }

    /*
     * questa funzione ritorna un elenco di raspberry, come secondo argomento (il primo è la key di autenticazione) è possibile passare
     * un id avendo cosi i dati relativi solo a quel raspberry
     *
     * Argument: $args("id") (opzionale) indica l'id del raspberry
     */
    function getRaspi($args){
        $return = null;
        $query = "select * from raspberry";
        if(isset($args["id"])){
            if(is_numeric($args["id"])) {
                $query .= " where enable=1 and id=" . $args["id"];
            }
        }
        if($tempRes = $this->modelDbConn->query($query)){
            $return[] = array("result"=>"success");
            while($row = $tempRes->fetch_assoc()){
                $return[] = $row;
            }
        }else{
            $return[] = array("result"=>"error");
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione ritorna un elenco di raspberry, come secondo argomento (il primo è la key di autenticazione) è necessario
     * fornire l'id dell'installazione avendo cosi i dati relativi ai raspberry di quella installazione
     *
     * Argument: $args("instId") indica l'id dell'installazione
     */
    function getRaspiFromInstId($args){
        $return = null;
        $query = "select * from raspberry";
        if(isset($args["instId"])){
            if(is_numeric($args["instId"])){
                $query .= " where enable=1 and instId=".$args["instId"]." order by tower,position";
                if($tempRes = $this->modelDbConn->query($query)){
                    $return[] = array("result"=>"success");
                    while($row = $tempRes->fetch_assoc()){
                        $return[] = $row;
                    }
                }else{
                    $return[] = array("result"=>"error");
                }
            }
        }else{
            $return[] = array("result"=>"error");
        }

        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione ritorna il raspberry con il nome indicato
     *
     * Argument: $args("name") indica il nome del raspberry
     */
    function getRaspiFromName($args){
        $return = null;
        if(isset($args["name"])){
            $query = "select * from raspberry r where r.name='".$args["name"]."' and r.enable=1";
            if($tempRes = $this->modelDbConn->query($query)){

                if($tempRes->num_rows==1){
                    $return[] = array("result"=>"success");
                    $return[] = $tempRes->fetch_assoc();
                }else{
                    $return[] = array("result"=>"error");
                }
            }else{
                $return[] = array("result"=>"error");
            }
        }else{
            $return[] = array("result"=>"error");
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione permette di avere l'elenco dei sensori collegati ad un raspberry, come parametro è necessario passare l'id
     * del raspberry del quale si vogliono i sensori, ritorna null nel caso non vi siano sensori o l'id del raspberry è errato
     *
     * Arguments: $args["id"] rappresenta l'id del raspberry
     */
    function getSensors($args){
        $return = null;
        $query = "select * from sensorOnRaspi";
        if(isset($args["id"])){
            if(is_numeric($args["id"])){
                $query .= " where raspId=".$args["id"];

                if($tempRes = $this->modelDbConn->query($query)){
                    $return[] = array("result"=>"success");
                    while($row = $tempRes->fetch_assoc()){
                        $queryGetSensor = "select s.id as 'id',s.type as 'type', ".
                            "sd.type as 'typeDesc', s.description as 'description', ".
                            "s.fromValue as 'fromValue', s.toValue as 'toValue' ".
                            "from sensors s join sensorType sd on sd.id=s.type where s.id=".$row["sensId"];

                        if($tempSens = $this->modelDbConn->query($queryGetSensor)){
                            $return[] = $tempSens->fetch_assoc();
                        }
                    }
                }else{
                    $return[] = array("result"=>"error");
                }
            }else{
                $return[] = array("result"=>"error");
            }
        }else{
            $return[] = array("result"=>"error");
        }

        $this->modelDbConn->close();

        return json_encode($return);
    }

    /*
     * questa funzione ritorna un array con i valori rilevati da un determinato sensore, ritorna null nel caso il sensore non esista
     * o non esistano dati
     */
    function getData($args){
        $return = null;
        $query = "select * from sensorData";
        if(isset($args["id"])){
            if(is_numeric($args["id"])){
                $query .= " where sensorId=".$args["id"];

                if($tempRes = $this->modelDbConn->query($query)){
                    $return[] = array("result"=>"success");
                    while($row = $tempRes->fetch_assoc()){
                        $return[] = $row;
                    }
                }else{
                    $return[] = array("result"=>"error");
                }
            }else{
                $return[] = array("result"=>"error");
            }
        }else{
            $return[] = array("result"=>"error");
        }

        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione ritorna un array con i valori rilevati dal giorno precedente a oggi da un determinato sensore,
     * i dati vengono ridotti ad uno ogni cinque per non appesantire i dati inviati
     */
    function getTodayData($args){
        $return = null;
        $query = "SELECT r.* FROM (
                                SELECT *
                                FROM sensorData ";
        $queryEnd ="  ORDER BY id DESC) r
                    CROSS
                    JOIN ( SELECT @i := 0 ) s
                    HAVING ( @i := @i + 1) MOD 5 = 1";

        if(isset($args["id"])){
            if(is_numeric($args["id"])){
                $query .= " where sensorId=".$args["id"]." and date>=NOW()- INTERVAL 1 DAY";
                if($tempRes = $this->modelDbConn->query($query.$queryEnd)){
                    $return[] = array("result"=>"success");
                    while($row = $tempRes->fetch_assoc()){
                        $return[] = $row;
                    }
                }else{
                    $return[] = array("result"=>"error");
                }
            }else{
                $return[] = array("result"=>"error");
            }
        }else{
            $return[] = array("result"=>"error");
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione ritorna un array con i valori rilevati dal giorno precedente a oggi da un determinato sensore
     */
    function getTodayDataFull($args){
        $return = null;
        $query = "SELECT * FROM sensorData";
        if(isset($args["id"])){
            if(is_numeric($args["id"])){
                $query .= " where sensorId=".$args["id"]." and date>=NOW()- INTERVAL 1 DAY";
                if($tempRes = $this->modelDbConn->query($query)){
                    $return[] = array("result"=>"success");
                    while($row = $tempRes->fetch_assoc()){
                        $return[] = $row;
                    }
                }else{
                    $return[] = array("result"=>"error");
                }
            }else{
                $return[] = array("result"=>"error");
            }
        }else{
            $return[] = array("result"=>"error");
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione ritorna un array limitato (1 rilevamento ogni 10) con i valori rilevati dal giorno precedente a oggi da un determinato sensore , ritorna null nel caso il sensore non esista
     * o non esistano dati
     */
    function getFromToData($args){
        $return = null;
        $query = "SELECT r.* FROM (
                                SELECT *
                                FROM sensorData ";
        $queryEnd ="  ORDER BY id DESC) r
                    CROSS
                    JOIN ( SELECT @i := 0 ) s
                    HAVING ( @i := @i + 1) MOD 10 = 1";
        if(isset($args["id"]) && isset($args["from"]) && isset($args["to"])){

            if(is_numeric($args["id"])){
                $query .= " where sensorId=".$args["id"]." and date>='".$args["from"]."' and date<='".$args["to"]."'";
                if($tempRes = $this->modelDbConn->query($query.$queryEnd)){
                    $return[] = array("result"=>"success");
                    while($row = $tempRes->fetch_assoc()){
                        $return[] = $row;
                    }
                }else{
                    $return[] = array("result"=>"error");
                }
            }else{
                $return[] = array("result"=>"error");
            }

        }else{
            $return[] = array("result"=>"error");
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione ritorna un array con i valori rilevati dal giorno precedente a oggi da un determinato sensore , ritorna null nel caso il sensore non esista
     * o non esistano dati
     */
    function getFromToDataFull($args){
        $return = null;
        $query = "SELECT * FROM sensorData ";
        if(isset($args["id"]) && isset($args["from"]) && isset($args["to"])){

            if(is_numeric($args["id"])){
                $query .= " where sensorId=".$args["id"]." and date>='".$args["from"]."' and date<='".$args["to"]."'";

                if($tempRes = $this->modelDbConn->query($query)){
                    $return[] = array("result"=>"success");
                    while($row = $tempRes->fetch_assoc()){
                        $return[] = $row;
                    }
                }else{
                    $return[] = array("result"=>"error");
                }
            }else{
                $return[] = array("result"=>"error");
            }

        }else{
            $return[] = array("result"=>"error");
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * Questa funzione, dato un id di un sensore, rilascia l'ultimo valore rilevato sullo stesso.
     */
    function getLastData($args){
        $return = null;
        $query = "select * from sensorData";
        if(isset($args["id"])){
            if(is_numeric($args["id"])){
                $query .= " where sensorId=".$args["id"]." ORDER BY date DESC limit 1 ";
                if($tempRes = $this->modelDbConn->query($query)){
                    $return[] = array("result"=>"success");
                    while($row = $tempRes->fetch_assoc()){
                        $return[] = $row;
                    }
                }
            }
        }
        else{
            $return[] = array("result"=>"error");
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }

    /*
     * questa funzione ritorna la configurazione di un dato sistema
    */
    function getConfiguration($args){
        $return = null;
        $query = "select * from configuration";
        if(isset($args["id"])){
            if(is_numeric($args["id"])){
                $query .= " where instId=".$args["id"];
            }
        }
        if($tempRes = $this->modelDbConn->query($query)){
            $return[] = array("result"=>"success");
            while($row = $tempRes->fetch_assoc()){
                $return[] = $row;
            }
        }else{
            $return[] = array("result"=>"error");
        }
        $this->modelDbConn->close();
        return json_encode($return);
    }
}