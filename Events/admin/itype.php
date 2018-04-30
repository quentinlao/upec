<?php
require("../auth/EtreAuthentifie.php");


$title = 'Ajout du Itype';
$path = "../";

include($path."header.php");
if($idm->getRole() == "admin"){

?>
        <h1><?php echo $title; ?></h1>
        <img src="<?php echo $path ;?>bootstrap/css/img/evenements.png" style="max-width: 25%;height:auto;"/><br>
<form action="itype.php" method="post">
    <tr><td>Nom de l'identification : </td><td><input type="text" name="itype" /></td></tr>
    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
</form>
    <h3>Listes des itypes</h3>
    <?php
    $SQL = "SELECT * FROM itypes";
    $res = $db->query($SQL);
    if ($res->rowCount() == 0)
        error("La liste est vide");
    else {
        ?>
        <div class="row">

        <table class="table table-striped table-bordered table-hover">
        <thead>

        <th>Tid</th>
        <th>Nom</th>

        </thead>
        <tbody>
        <?php
        foreach ($res as $row) {
            ?>

            <tr>
                <td><?php echo $row['tid'] ?></td>
                <td><?php echo($row['nom']) ?></td>

            </tr>

            <?php
        }
    }
        ?>


        </tbody>
        </table>
    <h3>Rappel</h3>
    <p>un moyen d’identification
        (passeport, carte membre, invitation, etc)</p>





        <?php
    if(isset($_POST['itype']) ){
        $valeur = $_POST['itype'];
        if ( $valeur == ""  ) {
            error("Vide");
        }
        else {// connexion a la BD
            $valeur = htmlspecialchars($valeur);
            if(strlen($valeur) > 40){
                error("Champs trop long, Max : 40 caractères");
            }
            else {
                $SQL = "INSERT INTO itypes VALUES (DEFAULT ,?)";
                $st = $db->prepare($SQL);
                $res = $st->execute(array( $valeur));
                if (!$res) // ou if ($st->rowCount() ==0)
                    error("Erreur d’ajout");
                else succes("L'ajout a été effectué");
            }
        }
    }


}
else{
    error("Tu n'es pas un administrateur");
}


include("../footer.php");
?>

