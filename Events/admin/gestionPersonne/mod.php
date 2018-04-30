<?php
$title="Modifier";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){

if (!isset($_GET["pid"])) {

}else {

        $id = $_GET["pid"];


        $SQL = "SELECT * FROM personnes WHERE pid=:id";
        $st = $db->prepare($SQL);
        $st->execute(array('id'=>"$id"));
        $res = $st->fetch();
        $nom = $res['nom'];
        $prenom = $res['prenom'];


        if ($st->rowCount() == 0) {
            error("Erreur de modification");
        } else if (!isset($_POST['nom']) || !isset($_POST['prenom'])) {
            include("mod_form.php");
        } else {

            $nom =  htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            if ($nom == "" || $prenom == "" ) {
                include("mod_form.php");
            } else {
                if(strlen($nom) > 30 || strlen($prenom) > 30){
                    error("Champs trop long, Max : 30 caractères");
                }
                else {
                    $SQL = "UPDATE personnes SET nom=?, prenom=? WHERE pid=? ";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($nom, $prenom, $id));
                    if (!$res) // ou if ($st->rowCount() ==0)
                        error("Erreur de modification");
                    else
                        succes("La modification a été effectuée");
                }
            }
            $db = null;
        }

}
echo "<a href=\"..\personnes.php\">Revenir à la page de départ.</a>";
?>
    <h2>Rappel</h2>
    <p>
        Afin de participer à l’événement toute personne doit s’identifier à l’aide d’un moyen d’identification
        (passeport, carte membre, invitation, etc). Les informations concernant les personnes et les différents
        types d’identification se trouvent dans les tables personnes et itypes.
    </p>
<?php
}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>