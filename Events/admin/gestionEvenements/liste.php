<?php
$title="Listes";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
if($idm->getRole() == "admin"){


include($path."header.php");





$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $SQL = "SELECT * FROM evenements INNER JOIN categories ON evenements.cid = categories.cid ";
    $res = $db->query($SQL);
    if ($res->rowCount() == 0)
        echo error("La liste est vide");
    else {
?>

        <h1>Gestions des évènements</h1>
        <div class="row">

        <table class="table table-striped table-bordered table-hover">
        <thead>
        <th>Eid</th>
        <th>Intitule</th>
        <th>Description</th>
        <th>Date Debut</th>
        <th>Date Fin</th>
        <th>Type</th>
        <th>Cid</th>
        <th>Nom</th>
        <th>Modifier</th>
        <th>Supprimer</th>

        </thead>
        <tbody>
        <?php
        foreach ($res as $row) {
            ?>

            <tr>
                <td><?php echo ($row['eid']) ?></td>
                <td><?php echo $row['intitule'] ?></td>
                <td><?php echo $row['description'] ?></td>
                <td><?php echo $row['dateDebut'] ?></td>
                <td><?php echo $row['dateFin'] ?></td>
                <td><?php echo $row['type'] ?></td>
                <td><?php echo $row['cid'] ?></td>
                <td><?php echo $row['nom'] ?></td>
              <td><a href="mod.php?eid=<?php echo $row['eid'];?>">  <button type="button" class="btn btn-lg btn-primary">Modifier</button></a></td>
                <td><a href="sup.php?eid=<?php echo $row['eid'];?>">  <button type="button" class="btn btn-lg btn-danger">Supprimer</button></a></td>

            </tr>

            <?php
        };
        ?>


        </tbody>
        </table>

        </div>


        <?php
        $db = null;
    }

echo "<a href=\"../evenements.php\">Revenir à la page de départ.</a>";
?>
    <h1>Exemple</h1>
    <img src="<?php echo $path;?>bootstrap/css/img/concert.png" />
    <h2>Evenement</h2>
    <b>Eid </b>: 0<br/>
    <b>Intitule </b>: Title<br>
    <b>Description </b>: <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi interdum, neque sed lacinia commodo, nibh dui porta libero, sit amet consequat lectus enim vel ex. Etiam aliquam consequat eros eu euismod. Integer fringilla ultrices aliquet. Morbi fringilla tempus nulla, nec molestie sem lobortis eu. Ut eu felis erat. Aliquam suscipit dui quis tincidunt pellentesque. Aenean vehicula sodales tellus ac sodales. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam imperdiet facilisis neque ut tristique.<br>
        <b>Date Debut </b>: 2017-04-21 00:00:00<br>
        <b>Date Fin </b>: 2017-04-21 00:00:00<br>
        <b>Type </b>: Ouvert<br>
        <b>Cid </b>: 0<br>
        <b>Nom de la catégorie </b>: Musique<br>
    </p>
<?php
}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>


