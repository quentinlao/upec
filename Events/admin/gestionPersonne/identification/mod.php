<?php
$title="Modifier";
$path = "../../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){


if (!isset($_GET["pid"]) || !isset($_GET["tid"])) {

}else {

        $id = $_GET["pid"];
         $tid = $_GET["tid"];


    $SQL = "SELECT * FROM identifications WHERE pid=:id AND tid=:tid";
        $st = $db->prepare($SQL);
        $st->execute(array('id'=>"$id",'tid'=>"$tid"));
        $res = $st->fetch();

        $valeur = $res['valeur'];
        $tid = $res['tid'];

        if ($st->rowCount() == 0) {
            error("Erreur de modification");
        } else if (!isset($_POST['valeur'])) {
            include("mod_form.php");
        } else {

            $valeur=  htmlspecialchars($_POST['valeur']);

            if ($valeur == "" ) {
                include("mod_form.php");
            } else {
                if(strlen($valeur) > 50 ){
                    error("Champs trop long, Max : 50 caractères");
                }
                else {
                    $SQL = "UPDATE identifications SET valeur=? WHERE pid=? AND tid=? ";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array( $valeur, $id,$tid));
                    if (!$res) // ou if ($st->rowCount() ==0)
                        error("Erreur de modification");
                    else
                        succes("La modification a été effectuée");
                }
            }
            $db = null;
        }

}
echo "<a href=\"..\liste.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>