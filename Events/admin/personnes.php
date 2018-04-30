<?php
require("../auth/EtreAuthentifie.php");


$title = 'Gestion des personnes';
$path = "../";

include($path."header.php");
if($idm->getRole() == "admin"){


if(!isset($_POST["gestion"])){
    ?>

    <h1>Gestion des personnes</h1>
    <img src="<?php echo $path ;?>bootstrap/css/img/personnes.png" style="max-width: 25%;height:auto;"/><br>
    <form action="personnes.php" method="post" >
        <div class="form-group">
        <select name="gestion" class="form-control">
            <option value=""> Gestion des personnes </option>
            <option value="affiche">Affiche</option>
            <option value="ajoute">Ajoute</option>
            <!-- <option value="modifie">Modification mdp</option> -->
            <!--  <option value="suppr">Supprime</option>
             <option value="modifie">Modifier</option> -->
        </select>
        </div>

        <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
    </form>
    <h2>Rappel</h2>
    <b class="red"> — Gestion des personnes : </b>liste, ajout, suppression, modification.<br>

    <?php
}

else{
    $affiche = "affiche";
    $ajoute= "ajoute";
    $suppr = "suppr";
    $modifie = "modifie";
    $gestion = $_POST["gestion"];
    if($gestion == $affiche) header ('Location:gestionPersonne/liste.php');
    else if($gestion == $ajoute) header ('Location:gestionPersonne/ajout.php');
    //  else if($gestion == $modifie) header ('Location:gestionUtilisateur/mod.php');
    //else if($gestion == $suppr) header ('Location:sup.php');

    echo "<a href=\"personnes.php\">Revenir à la page de départ.</a>";
}


}
else{
    error("Tu n'es pas un administrateur");
}

include("../footer.php");
?>

