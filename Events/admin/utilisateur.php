<?php
require("../auth/EtreAuthentifie.php");


$title = 'Gestion des utilisateurs';
$path = "../";

include($path."header.php");
if($idm->getRole() == "admin"){


if(!isset($_POST["gestion"])){
    ?>

    <h1>Gestion des utilisateurs</h1>
    <img src="<?php echo $path ;?>bootstrap/css/img/users.png" style="max-width: 25%;height:auto;"/><br>
    <b class="red"> — Gestion des utilisateurs : </b>liste, ajout, modification mdp.<br><br>
    <div class="form-group"><label class="red bold">Choisir une manipulation : </label>

    <form action="utilisateur.php" method="post">
        <select name="gestion" class="form-control">
            <option value=""> Gestion des utilisateurs </option>
            <option value="affiche">Affiche</option>
            <option value="ajoute">Ajoute</option>
            <!-- <option value="modifie">Modification mdp</option> -->
            <!--  <option value="suppr">Supprime</option>
             <option value="modifie">Modifier</option> -->
        </select>

        <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
    </form>
    </div>
    <h2>Rappel</h2>
    <p>
        <span class="bold red" >User :</span> utilisateur<br>
        <span class="bold red" >Admin :</span> Administrateur<br>
    </p>
    <?php
}

else{
    $affiche = "affiche";
    $ajoute= "ajoute";
    $suppr = "suppr";
    $modifie = "modifie";
    $gestion = $_POST["gestion"];
    if($gestion == $affiche) header ('Location:gestionUtilisateur/liste.php');
    else if($gestion == $ajoute) header ('Location:gestionUtilisateur/ajout.php');
  //  else if($gestion == $modifie) header ('Location:gestionUtilisateur/mod.php');
    //else if($gestion == $suppr) header ('Location:sup.php');

    echo "<a href=\"utilisateur.php\">Revenir à la page de départ.</a>";
}


}
else{
    error("Tu n'es pas un administrateur");
}

include("../footer.php");
?>

