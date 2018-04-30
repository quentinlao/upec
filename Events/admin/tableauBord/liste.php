<?php
$title="Listes";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){



    ?>
    <h1>Tableau de bord</h1>
    <img src="<?php echo $path ;?>bootstrap/css/img/tableaubord.png" style="max-width: 25%;height:auto;"/><br>
    <form action="liste.php" method="post">
        <div class="form-group">
            <select name="gestion" class="form-control">
                <option value="">------------------------ Choix ------------------------</option>
                <option value="evenement">Par événement</option>
                <option value="personne">Par personne</option>
                <option value="date">Par date</option>
                <option value="all">All</option>
            </select>

            <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Valider"/>
        </div>
    </form>

    <?php

    if(isset($_POST['gestion'])) {
        $evenement = "evenement";
        $personne = "personne";
        $date = "date";
        $all = "all";
        $gestion = $_POST['gestion'];
        if ($gestion == $evenement) {
            include("liste.evenement.php");
        }
        if ($gestion == $personne) {
            include("liste.personne.php");
        }
        if ($gestion == $date) {
            include("liste.date.php");
        }
        if ($gestion == $all) {
            include("liste.all.php");
        }
    }
    echo "<a href=\"../tableauBord.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}
include($path."/footer.php");
?>


