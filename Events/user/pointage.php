<?php
require("../auth/EtreAuthentifie.php");


$title = 'Pointage d\'une personne';
$path = "../";

include($path . "header.php");


//Récupération des données
if (!isset($_POST['evenement']) || !isset($_POST['nom']) || !isset($_POST['prenom']) ||!isset($_POST['date'])) {
    include("pointage_form.php");

} else {
    $evenement = $_POST['evenement'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date = $_POST['date'];

    // Vérification
    if (!$evenement > 0 || $nom == "" || $prenom == "" || $date =="" ) {
        include("pointage_form.php");
    }
    else {
        //VERIFICATION SI IL POINTE A LA BONNE DATE
        $SQL = "SELECT * FROM evenements WHERE eid=?";
        $st = $db->prepare($SQL);
        $res = $st->execute(array($evenement));
        $etat = 0;
        $date = $date . " 00:00:00";

        foreach ($st as $row) {
            if($row['dateDebut'] > $date || $date > $row['dateFin']) {
                $etat = 1;
                error("La date de début est " . $row['dateDebut'] . " et la fin " . $row['dateFin'] . ".<br> Ta date : " . $date);

            }
        }

        if ($etat == 0) {



        //VERIFICATION NOM ET PRENOM
        $SQL = "SELECT * FROM personnes WHERE nom=? AND prenom=?";
        $st = $db->prepare($SQL);
        $res = $st->execute(array($nom, $prenom));
        foreach ($st as $row) {
            $personne = $row['pid'];
        }
        //LA PERSONNE N'EXISTE PAS-------------------------------------------------------------
        if ($st->rowCount() == 0) {
            error("La personne n'existe pas");
            //VERIFICATION SI EVENTS OUVERT OU FERME
            $SQL = "SELECT * FROM evenements WHERE eid=?";
            $st = $db->prepare($SQL);
            $res = $st->execute(array($evenement));
            foreach ($st as $row) {
                $type = $row['type'];
            }
            //L'EVENEMENT EST OUVERT------------------------------------------------------------------
            if ($type == "ouvert") {
                succes("Ajout dans le système d'information avec l'itype : invité");
                //AJOUT DE LA PERSONNE
                $SQL = "INSERT INTO personnes VALUES (DEFAULT,?,?)";
                $st = $db->prepare($SQL);
                $res = $st->execute(array($nom, $prenom));
                if (!$res) error("Erreur d’ajout de la personne");
                else {
                    succes("L'ajout de la personne a été effectué ");
                    //JE RECUPERE SON PID MTN
                    //VERIFICATION NOM ET PRENOM
                    $SQL = "SELECT * FROM personnes WHERE nom=? AND prenom=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($nom, $prenom));
                    foreach ($st as $row) {
                        $personne = $row['pid'];
                    }
                    $nom = "Invite";
                    //JE VERIFIE SI LE ITYPE INVITE EXISTE PAS DEJA
                    $SQL = "SELECT * FROM itypes WHERE nom=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($nom));
                    $test = "";
                    foreach ($st as $row) {
                        $test = $row['nom'];
                    }
                    if ($test == "Invite") {
                        //JE RECUPERE SON TID
                        $tid;
                        $SQL = "SELECT * FROM itypes WHERE nom=?";
                        $st = $db->prepare($SQL);
                        $res = $st->execute(array($nom));
                        foreach ($st as $row) {
                            $tid = $row['tid'];
                        }
                    } else {
                        //JE CREE UN ITYPE INVITE
                        $SQL = "INSERT INTO itypes VALUES (DEFAULT,?)";
                        $st = $db->prepare($SQL);
                        $res = $st->execute(array($nom));
                        //JE RECUPERE SON TID
                        $tid;
                        $SQL = "SELECT * FROM itypes WHERE nom=?";
                        $st = $db->prepare($SQL);
                        $res = $st->execute(array($nom));
                        foreach ($st as $row) {
                            $tid = $row['tid'];
                        }
                    }

                    //JE LUI AJOUTE UNE IDENTIFICATION
                    $SQL = "INSERT INTO identifications VALUES (?,?,?)";
                    $st = $db->prepare($SQL);
                    $valeur = "Ok invite";

                    $res = $st->execute(array($personne, $tid, $valeur));
                    if (!$res) error("Erreur d’ajout");
                    else succes("L'ajout de l'inditification a été effectué");

                    //J'AJOUTE SA PARTICIPATION
                    $req = $db->prepare("INSERT INTO participations(eid,pid,date,uid) VALUES (:eid,:pid,:date,:uid)");
                    $req->execute(array(
                        'eid' => $evenement,
                        'pid' => $personne,
                        'date' => $date,
                        'uid' => $idm->getUid()
                    ));
                    succes("L'ajout de la participation a été effectué");

                    //JE L'INSCRIS (RELIE PARTICIPATION ET L'EVENEMENT)
                    //JE VERIFIE SI IL EST PAS DEJA INSCRIT--------------------------------------------------------
                    $SQL = "SELECT * FROM inscriptions WHERE pid=? AND eid=? AND uid=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($personne, $evenement, $idm->getUid()));
                    if ($st->rowCount() == 0) {
                        $req = $db->prepare("INSERT INTO inscriptions (pid,eid,uid) VALUES (:pid,:eid,:uid)");
                        $req->execute(array(
                            'pid' => $personne,
                            'eid' => $evenement,
                            'uid' => $idm->getUid()
                        ));
                        succes("L'ajout de l'inscription a été effectué");
                    }
                }

            } else {
                error("L'événement est ferme");
            }
        } else {
            //VERIFICATION IDENTIFICATION----------------------------------------------
            $SQL = "SELECT * FROM identifications  INNER JOIN itypes ON identifications.tid = itypes.tid WHERE pid=:id ";
            $st = $db->prepare($SQL);
            $st->execute(array('id' => "$personne"));
            if ($st->rowCount() == 0) {
                error("La personne n'a pas d'identification");
                //VERIFICATION SI EVENTS OUVERT OU FERME
                $SQL = "SELECT * FROM evenements WHERE eid=?";
                $st = $db->prepare($SQL);
                $res = $st->execute(array($evenement));
                foreach ($st as $row) {
                    $type = $row['type'];
                }
                if ($type == "ouvert") {
                    succes("Ajout dans le système d'information avec l'itype : invité");
                    //JE RECUPERE SON PID MTN
                    //VERIFICATION NOM ET PRENOM
                    $SQL = "SELECT * FROM personnes WHERE nom=? AND prenom=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($nom, $prenom));
                    foreach ($st as $row) {
                        $personne = $row['pid'];
                    }

                    $nom = "Invite";
                    //JE VERIFIE SI LE ITYPE INVITE EXISTE PAS DEJA
                    $SQL = "SELECT * FROM itypes WHERE nom=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($nom));
                    $test;
                    foreach ($st as $row) {
                        $test = $row['nom'];
                    }
                    if ($test == "Invite") {
                        //JE RECUPERE SON TID
                        $SQL = "SELECT * FROM itypes WHERE nom=?";
                        $st = $db->prepare($SQL);
                        $res = $st->execute(array($nom));
                        foreach ($st as $row) {
                            $tid = $row['tid'];
                        }
                    } else {
                        //JE CREE UN ITYPE INVITE
                        $SQL = "INSERT INTO itypes VALUES (DEFAULT,?)";
                        $st = $db->prepare($SQL);
                        $res = $st->execute(array($nom));
                    }
                    //JE LUI AJOUTE UNE IDENTIFICATION
                    $SQL = "INSERT INTO identifications VALUES (?,?,?)";
                    $st = $db->prepare($SQL);
                    $valeur = "Ok invite";
                    $res = $st->execute(array($personne, $tid, $valeur));
                    if (!$res) // ou if ($st->rowCount() ==0)
                        error("Erreur d’ajout");
                    else succes("L'ajout de l'inditification a été effectué");

                    //J'AJOUTE SA PARTICIPATION

                    $req = $db->prepare("INSERT INTO participations(eid,pid,date,uid) VALUES (:eid,:pid,:date,:uid)");
                    $req->execute(array(
                        'eid' => $evenement,
                        'pid' => $personne,
                        'date' => $date,
                        'uid' => $idm->getUid()
                    ));
                    succes("L'ajout de la participation a été effectué");
                    //JE L'INSCRIS (RELIE PARTICIPATION ET L'EVENEMENT)
                    $req = $db->prepare("INSERT INTO inscriptions (pid,eid,uid) VALUES (:pid,:eid,:uid)");
                    $req->execute(array(
                        'pid' => $personne,
                        'eid' => $evenement,
                        'uid' => $idm->getUid()
                    ));
                    succes("L'ajout de l'inscription a été effectué");
                } //LE CAS OU LA PERSONNE EXISTE ET VEUT ALLER A UN EVENT FERME ET N'A PAS D'IDENTIFICATION
                else {
                    error("L'événement est ferme");
                }
                //CONDTION IDENTIFICATION
            } else {
                $nom;
                foreach ($st as $row) {
                    $nom = $row['nom'];
                    succes("<u>Identification</u> :" . $row['nom'] . " <u>Etat</u> : " . $row['valeur']);
                }
                //PERSONNE EXISTE, IDENTIFICATION EXISTE
                //VERIFICATION SI EVENTS OUVERT OU FERME
                $SQL = "SELECT * FROM evenements WHERE eid=?";
                $st = $db->prepare($SQL);
                $res = $st->execute(array($evenement));
                foreach ($st as $row) {
                    $type = $row['type'];
                }
                if ($type == "ouvert") {

                    //JE RECUPERE SON PID MTN
                    //VERIFICATION NOM ET PRENOM
                    $SQL = "SELECT * FROM personnes WHERE nom=? AND prenom=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($nom, $prenom));
                    foreach ($st as $row) {
                        $personne = $row['pid'];
                    }
                    //JE RECUPERE SON TID
                    $SQL = "SELECT * FROM itypes WHERE nom=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($nom));
                    $tid;
                    foreach ($st as $row) {
                        $tid = $row['tid'];
                    }
                    //J'AJOUTE SA PARTICIPATION

                    $req = $db->prepare("INSERT INTO participations(eid,pid,date,uid) VALUES (:eid,:pid,:date,:uid)");
                    $req->execute(array(
                        'eid' => $evenement,
                        'pid' => $personne,
                        'date' => $date,
                        'uid' => $idm->getUid()
                    ));
                    succes("L'ajout de la participation a été effectué");
                    //JE L'INSCRIS (RELIE PARTICIPATION ET L'EVENEMENT)
                    //SI IL EST DEJA INSCRIT
                    //VERIFICATION SI IL EST DEJA INSCRIT


                    $SQL = "SELECT * FROM inscriptions WHERE pid=? AND eid=? AND uid=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($personne, $evenement, $idm->getUid()));


                    if ($st->rowCount() == 0) {
                        $req = $db->prepare("INSERT INTO inscriptions (pid,eid,uid) VALUES (:pid,:eid,:uid)");
                        $req->execute(array(
                            'pid' => $personne,
                            'eid' => $evenement,
                            'uid' => $idm->getUid()
                        ));
                        succes("L'ajout de l'inscription a été effectué");
                    }

                } else {
                    //JE RECUPERE SON PID MTN
                    //VERIFICATION NOM ET PRENOM
                    $SQL = "SELECT * FROM personnes WHERE nom=? AND prenom=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($nom, $prenom));
                    foreach ($st as $row) {
                        $personne = $row['pid'];
                    }
                    //PERSONNE EXISTE, IDENTIFICATION EXISTE, EVENEMENT FERME
                    //VERIFICATION SI IL EST DEJA INSCRIT

                    $SQL = "SELECT * FROM inscriptions WHERE pid=? AND eid=? AND uid=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($personne, $evenement, $idm->getUid()));
                    if ($st->rowCount() == 0) {
                        error("Tu n'es pas inscrit");
                    } //LA PERSONNE EST INSCRIT
                    else {
                        //J'AJOUTE SA PARTICIPATION

                        $req = $db->prepare("INSERT INTO participations(eid,pid,date,uid) VALUES (:eid,:pid,:date,:uid)");
                        $req->execute(array(
                            'eid' => $evenement,
                            'pid' => $personne,
                            'date' => $date,
                            'uid' => $idm->getUid()
                        ));
                        succes("Ajout de la participation");
                    }
                }
            }

        }
     }
    }
}

echo "<a href=\"pointage.php\">Revenir à la page de départ.</a>";
?>
<h3>Attention</h3>
<p>Si tu es un invité et que tu n'es pas inscrit dans un event ouvert. On te crée un itype "Ok invité" et on te rajoute à la bdd personnes.</p>
<?php
include($path . "/footer.php");


?>