<?php
require("../auth/EtreAuthentifie.php");


$title = 'Gestion des événements';
$path = "../";

include($path."header.php");
if($idm->getRole() == "admin"){


if(!isset($_POST["gestion"])){
    ?>

    <h1><?php echo $title; ?></h1>
    <img src="<?php echo $path ;?>bootstrap/css/img/evenements.png" style="max-width: 25%;height:auto;"/><br>

    <form action="evenements.php" method="post">
        <select name="gestion" class="form-control" >
            <option value=""> Gestions des types d'identifications </option>
            <option value="affiche">Affiche</option>
            <option value="ajoute">Ajoute</option>
            <!--  <option value="suppr">Supprime</option>
             <option value="modifie">Modifier</option> -->
        </select>
        <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
    </form

    <?php
}

else{
    $affiche = "affiche";
    $ajoute= "ajoute";
    $suppr = "suppr";
    $modifie = "modifie";
    $gestion = $_POST["gestion"];
    if($gestion == $affiche) header ('Location:gestionEvenements/liste.php');
    else if($gestion == $ajoute) header ('Location:gestionEvenements/ajout.php');
    //else if($gestion == $suppr) header ('Location:sup.php');
    //  else if($gestion == $modifie) header ('Location:mod.php');
    echo "<a href=\"evenements.php\">Revenir à la page de départ.</a>";
}

}
else{
    error("Tu n'es pas un administrateur");
}


include("../footer.php");
?>

