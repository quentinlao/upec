<?php
require("../auth/EtreAuthentifie.php");


$title = 'Soumission en batch';
$path = "../";

include($path . "header.php");
$SQL = "SELECT * FROM evenements INNER JOIN categories ON evenements.cid = categories.cid ";
$res = $db->query($SQL);
if ($res->rowCount() == 0)
echo error("<p>La liste est vide</p>");
else {
    ?>
    <h1>Soummission en batch</h1>


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
            <th>Participe</th>


            </thead>
            <tbody>
            <form action="check.php" method="post">
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
                    <td>

                        <div class="checkbox">
                            <label><input type="checkbox" name="checkBoxName[]" value="<?php echo $row['eid']; ?>" /></label>
                        </div>

                    </td>


                </tr>

                <?php
            };
            ?>


            </tbody>
        </table>
        <h3>Pour la personne </h3>
        <select name="pid">
            <option value="">-------------------- Pid -----------------</option>
            <?php
            $SQL = "SELECT * FROM personnes";
            $res = $db->query($SQL);
            if ($res->rowCount() == 0)
                echo "La liste est vide";
            else {
                ?>
                <div class="row">
                    <?php
                    foreach ($res as $row) {
                        ?>
                        <option value="<?php echo ($row['pid']) ?>"><?php echo ($row['pid']) ?> | <?php echo ($row['nom']) ?></option>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </select>
        <h3>Pour la date </h3>
        <input type="date" name="date"  />
        <h3>Envoie</h3>
        <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Submit" />
    </form>



    <?php
    $db = null;
}

echo "<a href=\"batch.php\">Revenir à la page de départ.</a>";


include($path . "/footer.php");


?>
</div>