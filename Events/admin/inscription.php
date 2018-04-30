<?php
require("../auth/EtreAuthentifie.php");


$title = 'Inscription';
$path = "../";

include($path."header.php");
if($idm->getRole() == "admin"){

    if (!isset($_POST['pid']) || !isset($_POST['eid']) || !isset($_POST['uid'])) {
        include("inscription_form.php");
    } else {
        $pid= $_POST['pid'];
        $eid= $_POST['eid'];
        $uid = $_POST['uid'];

// Vérification
        if (!is_numeric($pid) || !$pid > 0 || !is_numeric($eid) || !$eid > 0 || !is_numeric($uid) || !$uid > 0 ) {
            include("inscription_form.php");
        }
        else {// connexion a la BD
            echo "Pid :" . $pid . "Eid : " .  $eid . "Uid : " . $uid;
            //VERIFICATION SI IL EXISTE PAS DEJA
            $SQL = "SELECT * FROM inscriptions WHERE pid=? AND eid=?";
            $st = $db->prepare($SQL);
            $res = $st->execute(array($pid, $eid));
            if ($st->rowCount() == 0)
            {

                $SQL = "INSERT INTO inscriptions VALUES (?,?,?)";
                $st = $db->prepare($SQL);
                $res = $st->execute(array($pid,$eid, $uid));
                if (!$res)    error("Erreur d’ajout");
                else succes("L'ajout a été effectué");
            }
            else{
                error("L'inscription existe déjà");
            }

        }
    }
    echo "<a href=\"inscription.php\">Revenir à la page de départ.</a>";





}
else{
    error("Tu n'es pas un administrateur");
}

include("../footer.php");
?>

