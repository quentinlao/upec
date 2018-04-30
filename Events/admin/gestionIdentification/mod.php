<?php
$title="Modifier";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
if($idm->getRole() == "admin"){

include($path."header.php");

?>
<h1>La modification</h1>
    <p>Le trouvent dans les tables <b>evenements</b> et <b>participation</b>.<br>
      Afin de participer à l’événement toute personne doit s’identifier à l’aide d’un moyen d’identification
      (passeport, carte membre, invitation, etc). Les informations concernant les personnes et les différents
      types d’identification se trouvent dans les tables personnes et itypes
  </p>
<?php
if (!isset($_GET["pid"])) {

}else {

        $id = $_GET["pid"];


        $SQL = "SELECT * FROM identifications WHERE pid=:id";
        $st = $db->prepare($SQL);
        $st->execute(array('id'=>"$id"));
        $res = $st->fetch();
        $tid= $res['tid'];
        $valeur = $res['valeur'];


        if ($st->rowCount() == 0) {
            error("Erreur de modification");
        } else if (!isset($_POST['tid']) || !isset($_POST['valeur'])) {
            include("mod_form.php");
        } else {

            $valeur=  htmlspecialchars($_POST['valeur']);

            if (!is_numeric($tid) ||  !$tid > 0 ||$valeur == "" ) {
                include("mod_form.php");
            } else {
                if(strlen($valeur) > 50 ){
                    error("Champs trop long, Max : 50 caractères");
                }
                else {
                    $SQL = "UPDATE identifications SET  valeur=? WHERE pid=? AND tid=?";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array( $valeur, $id, $tid,));
                    if (!$res) // ou if ($st->rowCount() ==0)
                        error("Erreur de modification");
                    else
                        succes("La modification a été effectuée");
                }
            }
            $db = null;
        }

}
echo "<a href=\"..\identification.php\">Revenir à la page de départ.</a>";
}
else{
    error("Tu n'es pas un administrateur");
}
include($path."/footer.php");
?>