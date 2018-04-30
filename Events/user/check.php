<?php
$title="Ajout check";
$path = "../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
?>

<?php
if(!empty($_POST['checkBoxName'])  ){
    $aCheck = $_POST['checkBoxName'];
    $pid = $_POST['pid'];
    $date = $_POST['date'];
    $date = $date . " 00:00:00";
    if (!empty($aCheck)  ) {
          $N = count($aCheck);
          succes("Tu as selectionné $N events ");



                for ($i = 0; $i < $N; $i++) {
                    echo "Choix : ". $aCheck[$i]. "<br>";
                    //VERIFICATION SI IL POINTE A LA BONNE DATE
                    $SQL = "SELECT * FROM evenements WHERE eid=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($aCheck[$i]));
                    $etat = 0;


                    foreach ($st as $row) {
                        if($row['dateDebut'] > $date || $date > $row['dateFin']) {
                            $etat = 1;
                            error("La date de début est " . $row['dateDebut'] . " et la fin " . $row['dateFin'] . ".<br> Ta date : " . $date);

                        }
                    }

                    if ($etat == 0) {
                        //VERIFICATION SI LA PARTICIPATION EXISTE DEJA------------------------------------------------
                        /*
                        $SQL = "SELECT * FROM participations WHERE pid=? AND eid=? AND uid=?";
                        $st = $db->prepare($SQL);
                        $res = $st->execute(array($pid, $aCheck[$i], $idm->getUid()));
                        if ($st->rowCount() == 0) {
                        */
                        //VERIFICATION SI LA PARTICIPATION EST OUVERT---------------------------------------------
                            $SQL = "SELECT * FROM evenements WHERE eid=?";
                            $st = $db->prepare($SQL);
                            $res = $st->execute(array($aCheck[$i]));
                            foreach ($st as $row) {
                                $type = $row['type'];
                            }
                            if ($type == "ouvert") {
                                //VERIFICATION SI L'INSCRIPTION EXISTE DEJA------------------------------------------
                                $SQL = "SELECT * FROM inscriptions WHERE pid=? AND eid=? AND uid=?";
                                $st = $db->prepare($SQL);
                                $res = $st->execute(array($pid, $aCheck[$i], $idm->getUid()));
                                if ($st->rowCount() == 0) {
                                    $req = $db->prepare("INSERT INTO inscriptions (pid,eid,uid) VALUES (:pid,:eid,:uid)");
                                    $req->execute(array(
                                        'pid' => $pid,
                                        'eid' => $aCheck[$i],
                                        'uid' => $idm->getUid()
                                    ));
                                    succes("L'ajout de l'inscription a été effectué");
                                }
                                $req = $db->prepare("INSERT INTO participations(eid,pid,date,uid) VALUES (:eid,:pid,:date,:uid)");
                                $req->execute(array(
                                    'eid' => $aCheck[$i],
                                    'pid' => $pid,
                                    'date' => $date,
                                    'uid' => $idm->getUid()
                                ));
                                succes("Ajout de la participation");
                            } else {
                                //SI LA PERSONNE A L'INDENTIFICATION----------------------------------------------------------
                                $SQL = "SELECT * FROM identifications  INNER JOIN itypes ON identifications.tid = itypes.tid WHERE pid=:id ";
                                $st = $db->prepare($SQL);
                                $st->execute(array('id' => "$pid"));
                                if ($st->rowCount() == 0) {
                                    error("Pas d'identification, tu ne peux pas participé à un événement fermé");
                                } else {
                                    //VERIFICATION SI L'INSCRIPTION EXISTE DEJA------------------------------------------
                                    $SQL = "SELECT * FROM inscriptions WHERE pid=? AND eid=? AND uid=?";
                                    $st = $db->prepare($SQL);
                                    $res = $st->execute(array($pid, $aCheck[$i], $idm->getUid()));
                                    if ($st->rowCount() == 0) {
                                        $req = $db->prepare("INSERT INTO inscriptions (pid,eid,uid) VALUES (:pid,:eid,:uid)");
                                        $req->execute(array(
                                            'pid' => $pid,
                                            'eid' => $aCheck[$i],
                                            'uid' => $idm->getUid()
                                        ));
                                        succes("L'ajout de l'inscription a été effectué");
                                    }
                                    $req = $db->prepare("INSERT INTO participations(eid,pid,date,uid) VALUES (:eid,:pid,:date,:uid)");
                                    $req->execute(array(
                                        'eid' => $aCheck[$i],
                                        'pid' => $pid,
                                        'date' => $date,
                                        'uid' => $idm->getUid()
                                    ));
                                    succes("Ajout de la participation");
                                }
                            }
                        echo "<hr><hr><br>";
                         /*
                        } else {
                            error("Il existe deja l'event : " . $aCheck[$i] . "pid : " . $pid . " uid : " . $idm->getUid() . " ");
                        }
                         */
                    }
                }

    }

}
else{
    error("Erreur de selection.");

}
echo "<a href=\"batch.php\">Revenir à la page de départ.</a>";
include($path."/footer.php");
?>

