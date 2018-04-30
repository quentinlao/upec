<?php
require("../auth/EtreAuthentifie.php");


$title = 'Soumission en batch';
$path = "../";

include($path . "header.php");
$SQL = "SELECT * FROM personnes ";
$res = $db->query($SQL);
if ($res->rowCount() == 0)

echo error("<p>La liste est vide</p>");
else {
?>
<h1>Soummission en batch</h1>


<div class="row">

    <table class="table table-striped table-bordered table-hover">
        <thead>
        <th>Pid</th>
        <th>Nom</th>
        <th>Prenom</th>
        <th>Selections</th>



        </thead>
        <tbody>
        <form action="check2.php" method="post">
            <?php
            foreach ($res as $row) {
                ?>

                <tr>
                    <td><?php echo $row['pid'] ?></td>
                    <td><?php echo $row['nom'] ?></td>
                    <td><?php echo $row['prenom'] ?></td>
                    <td>

                        <div class="checkbox">
                            <label><input type="checkbox" name="checkBoxName[]" value="<?php echo $row['pid']; ?>" /></label>
                        </div>

                    </td>


                </tr>

                <?php
            };
            ?>


        </tbody>
    </table>
    <h3>Pour l'événement </h3>
    <select name="eid">
        <option value="">-------------------- Eid -----------------</option>
        <?php
        $SQL = "SELECT * FROM evenements";
        $res = $db->query($SQL);
        if ($res->rowCount() == 0)
            echo error("La liste est vide");
        else {
            ?>
            <div class="row">
                <?php
                foreach ($res as $row) {
                    ?>
                    <option value="<?php echo ($row['eid']) ?>"><?php echo ($row['eid']) ?> | <?php echo ($row['intitule']) ?> | <?php echo $row['type']?>  ||  <?php  echo "Du ". ($row['dateDebut']) . " au " . ($row['dateFin'])?></option>
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

    echo "<a href=\"batch2.php\">Revenir à la page de départ.</a>";


    include($path . "/footer.php");


    ?>
</div>