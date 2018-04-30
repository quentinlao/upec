<?php
require("../auth/EtreAuthentifie.php");


$title = 'Gestion des types d\'identifications';
$path = "../";

include($path."header.php");
if($idm->getRole() == "admin"){


if(!isset($_POST["gestion"])){
    ?>

<h1>Gestions des types d'identifications</h1>
    <img src="<?php echo $path ;?>bootstrap/css/img/identification.png" style="max-width: 25%;height:auto;"/><br>
    <form action="identification.php" method="post" >
        <select name="gestion" class="form-control">
            <option value=""> Gestions des types d'identifications </option>
            <option value="affiche">Affiche</option>
            <option value="ajoute">Ajoute</option>
            <!--  <option value="suppr">Supprime</option>
             <option value="modifie">Modifier</option> -->
        </select>
        <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
    </form>

    <?php
}

else{
    $affiche = "affiche";
    $ajoute= "ajoute";
    $suppr = "suppr";
    $modifie = "modifie";
    $gestion = $_POST["gestion"];
    if($gestion == $affiche) header ('Location:gestionIdentification/liste.php');
    else if($gestion == $ajoute) header ('Location:gestionIdentification/ajout.php');
    //else if($gestion == $suppr) header ('Location:sup.php');
    //  else if($gestion == $modifie) header ('Location:mod.php');
    echo "<a href=\"identification.php\">Revenir à la page de départ.</a>";
}


}
else{
    error("Tu n'es pas un administrateur");
}

include("../footer.php");
?>

