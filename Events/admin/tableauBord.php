<?php
require("../auth/EtreAuthentifie.php");


$title = 'tableau de bord';
$path = "../";

include($path."header.php");
if($idm->getRole() == "admin"){


if(!isset($_POST["gestion"])){
    ?>

<h1><?php echo $title;?></h1>  <img src="<?php echo $path ;?>bootstrap/css/img/tableau.png" style="max-width: 25%;height:auto;"/><br>
    <form action="tableauBord.php" method="post">
        <div class="form-group">
        <select class="form-control" name="gestion">
            <option value="">-------------------------------------- Tableau de bord --------------------------------------</option>
            <option value="affiche">Affichage des participations aux événements</option>
            <option value="participe">Affichage des événements pour chaque personne auxquels elle a été inscrite mais participe pas</option>
            <option value="personne">Affichage des personnes pour chaque événements inscrites qui n'ont pas participé </option>
        </select>
        </div>
        <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Valider"/>
    </form>

    <?php
}

else{
    $affiche = "affiche";
    $participe= "participe";
    $personne= "personne";
    $gestion = $_POST["gestion"];
    if($gestion == $affiche) header ('Location:tableauBord/liste.php');
    else if($gestion == $participe) header ('Location:tableauBord/participe.php');
    else if($gestion == $personne) header ('Location:tableauBord/personne.php');
}


echo "<h2>Rappel</h2>
<br>
— <b>Tableau de bord : </b>affichage des participations aux événements (par événement, par personne,
par date).<br><br>
—  <b>Tableau de bord : </b> pour chaque personne affichage des événements auxquels elle a été inscrite,
mais auxquels elle n’a pas participé.<br><br>
— <b>Tableau de bord :</b> pour chaque événement affichage des personnes inscrites qui n’ont pas participé
à l’événement.<br>
<h3>Utilisation des tables</h3>
Lien événements -> Participations -> Personnes
";

}
else{
    error("Tu n'es pas un administrateur");
}
include("../footer.php");
?>

