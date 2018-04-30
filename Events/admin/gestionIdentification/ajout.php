<?php
$title="Ajouts d'identifications";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
if($idm->getRole() == "admin"){

include($path."header.php");


//Récupération des données
if (!isset($_POST['pid']) || !isset($_POST['tid']) || !isset($_POST['valeur'])) {
    include("aj_form.php");
} else {
    $pid= $_POST['pid'];
    $tid= $_POST['tid'];
    $valeur = $_POST['valeur'];

// Vérification
    if (!is_numeric($pid) || !$pid > 0 || $valeur == "" || !is_numeric($tid) || !$tid > 0 ) {
        include("aj_form.php");
    }
    else {// connexion a la BD

        $valeur = htmlspecialchars($valeur);
        if(strlen($valeur) > 50){
            error("Champs trop long, Max : 50 caractères");
        }
        else {
            $SQL = "INSERT INTO identifications VALUES (?,?,?)";
            $st = $db->prepare($SQL);
            $res = $st->execute(array($pid,$tid, $valeur));
            if (!$res) // ou if ($st->rowCount() ==0)
                error("Erreur d’ajout");
            else succes("L'ajout a été effectué");
        }
    }
}
echo "<a href=\"..\identification.php\">Revenir à la page de départ.</a>";
}
else{
    error("Tu n'es pas un administrateur");
}
include($path."/footer.php");
?>