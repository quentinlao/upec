<?php
$title="Listes";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){





$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $SQL = "SELECT * FROM personnes";
    $res = $db->query($SQL);
    if ($res->rowCount() == 0)

        echo error("<p>La liste est vide</p>");
    else {
?>
        <h1>Gestions des personnes</h1>

        <div class="row">

        <table class="table table-striped table-bordered table-hover">
        <thead>
        <th>Pid</th>
        <th>Nom</th>
        <th>Prenom</th>
        <th>Modifier</th>
        <th>Supprimer</th>

        </thead>
        <tbody>
        <?php
        foreach ($res as $row) {
            ?>

            <tr>
                <td><a href="listeidentification.php?pid=<?php echo $row['pid'];?>"> <?php echo ($row['pid']) ?></a></td>
                <td><?php echo $row['nom'] ?></td>
                <td><?php echo $row['prenom'] ?></td>
              <td><a href="mod.php?pid=<?php echo $row['pid'];?>">  <button type="button" class="btn btn-lg btn-primary">Modifier</button></a></td>
                <td><a href="sup.php?pid=<?php echo $row['pid'];?>">  <button type="button" class="btn btn-lg btn-danger">Supprimer</button></a></td>

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

echo "<a href=\"..\personnes.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>


