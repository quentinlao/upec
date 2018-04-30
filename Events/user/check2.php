<?php
$title="Ajout check";
$path = "../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
?>

<?php
if(!empty($_POST['checkBoxName'])  ){
    $aCheck = $_POST['checkBoxName'];
    $eid = $_POST['eid'];
    $date = $_POST['date'];

    if (!empty($aCheck)  ) {
        $N = count($aCheck);
        succes("Tu as selectionnes $N personnes ");


        for ($i = 0; $i < $N; $i++) {
            echo "Choix : ". $aCheck[$i]. "<br>";
            //VERIFICATION SI LA PARTICIPATION EXISTE DEJA------------------------------------------------
            /*
            $SQL = "SELECT * FROM participations WHERE pid=? AND eid=? AND uid=?";
            $st = $db->prepare($SQL);
            $res = $st->execute(array($aCheck[$i],$eid, $idm->getUid()));
            if ($st->rowCount() == 0) {
            */
                //VERIFICATION SI LA PARTICIPATION EST OUVERT---------------------------------------------
                $SQL = "SELECT * FROM evenements WHERE eid=?";
                $st = $db->prepare($SQL);
                $res = $st->execute(array($eid));
                foreach ($st as $row) {
                    $type = $row['type'];
                }
                if ($type == "ouvert") {
                    //VERIFICATION SI L'INSCRIPTION EXISTE DEJA------------------------------------------
                    $SQL = "SELECT * FROM inscriptions WHERE pid=? AND eid=? AND uid=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($aCheck[$i],$eid,$idm->getUid()));
                    if ($st->rowCount() == 0) {
                        $req = $db->prepare("INSERT INTO inscriptions (pid,eid,uid) VALUES (:pid,:eid,:uid)");
                        $req->execute(array(
                            'pid' => $aCheck[$i],
                            'eid' => $eid,
                            'uid' => $idm->getUid()
                        ));
                        succes("L'ajout de l'inscription a été effectué");
                    }
                    $req = $db->prepare("INSERT INTO participations(eid,pid,date,uid) VALUES (:eid,:pid,:date,:uid)");
                    $req->execute(array(
                        'eid' => $eid,
                        'pid' => $aCheck[$i],
                        'date' => $date,
                        'uid' => $idm->getUid()
                    ));
                    succes("Ajout de la participation");
                }
                else{
                    //SI LA PERSONNE A L'INDENTIFICATION----------------------------------------------------------
                    $SQL = "SELECT * FROM identifications  INNER JOIN itypes ON identifications.tid = itypes.tid WHERE pid=:id ";
                    $st = $db->prepare($SQL);
                    $st->execute(array('id' => "$aCheck[$i]"));
                    if ($st->rowCount() == 0){
                        error("Pas d'identification, tu ne peux pas participé à un événement fermé");
                    }
                    else{
                        //VERIFICATION SI L'INSCRIPTION EXISTE DEJA------------------------------------------
                        $SQL = "SELECT * FROM inscriptions WHERE pid=? AND eid=?";
                        $st = $db->prepare($SQL);
                        $res = $st->execute(array($aCheck[$i], $eid));
                        if ($st->rowCount() == 0) {

                            $req = $db->prepare("INSERT INTO inscriptions (pid,eid,uid) VALUES (:pid,:eid,:uid)");
                            $req->execute(array(
                                'pid' => $aCheck[$i],
                                'eid' => $eid,
                                'uid' => $idm->getUid()
                            ));
                            succes("L'ajout de l'inscription a été effectué");
                        }
                        else{
                            error("La personne : " . $aCheck[$i] . " est déjà inscrit à l'event : " .$eid. ". ");
                        }
                        $req = $db->prepare("INSERT INTO participations(eid,pid,date,uid) VALUES (:eid,:pid,:date,:uid)");
                        $req->execute(array(
                            'eid' => $eid,
                            'pid' => $aCheck[$i],
                            'date' => $date,
                            'uid' => $idm->getUid()
                        ));
                        succes("Ajout de la participation");
                    }
                }
                echo "<hr><hr><br>";
           /*
             }
            else {
                error("La personne : " . $aCheck[$i] . " participe déjà à l'event : " .$eid. ". ");
            }
           */
        }

    }

}
else{
    error("Erreur de selection.");

}
echo "<a href=\"batch2.php\">Revenir à la page de départ.</a>";
include($path."/footer.php");
?>

