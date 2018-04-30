<?php
require("../auth/EtreAuthentifie.php");


$title = 'Ajout catégorie';
$path = "../";

include($path."header.php");
if($idm->getRole() == "admin"){

    ?>
    <h1><?php echo $title; ?></h1>
    <img src="<?php echo $path ;?>bootstrap/css/img/evenements.png" style="max-width: 25%;height:auto;"/><br>
    <form action="categorie.php" method="post">
        <tr><td>Nom de la catégorie : </td><td><input type="text" name="nom" /></td></tr>
        <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
    </form>
    <h3>Listes des catégories</h3>
    <?php
    $SQL = "SELECT * FROM categories";
    $res = $db->query($SQL);
    if ($res->rowCount() == 0)
        error("La liste est vide");
    else {
        ?>
        <div class="row">

        <table class="table table-striped table-bordered table-hover">
        <thead>

        <th>Cid</th>
        <th>Nom</th>

        </thead>
        <tbody>
        <?php
        foreach ($res as $row) {
            ?>

            <tr>
                <td><?php echo $row['cid'] ?></td>
                <td><?php echo($row['nom']) ?></td>

            </tr>

            <?php
        }
    }
    ?>


    </tbody>
    </table>


    <h3>Rappel</h3>
    <p>
        Exemple : Comedie, Musique, Art, Dance, ...
    </p>
    <?php
    if(isset($_POST['nom']) ){
        $valeur = $_POST['nom'];
        if ( $valeur == ""  ) {
            error("Vide");
        }
        else {// connexion a la BD
            $valeur = htmlspecialchars($valeur);
            if(strlen($valeur) > 30){
                error("Champs trop long, Max : 30 caractères");
            }
            else {
                $SQL = "INSERT INTO categories VALUES (DEFAULT ,?)";
                $st = $db->prepare($SQL);
                $res = $st->execute(array($valeur));
                if (!$res) // ou if ($st->rowCount() ==0)
                    error("Erreur d’ajout");
                else {
                    succes("L'ajout a été effectué");



                }
            }
        }
    }



}
else{
    error("Tu n'es pas un administrateur");
}


include("../footer.php");
?>

        </div>