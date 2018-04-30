<?php

?>
<h1><?php echo $title;?></h1>


<?php
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $SQL = "SELECT * FROM personnes";
    $res = $db->query($SQL);
    if ($res->rowCount() == 0)
    echo error("La liste est vide");
    else {
    ?>
    <h2>Gestions des personnes</h2>
    <div class="row">

        <table class="table table-striped table-bordered table-hover">
            <thead>
            <th>Pid</th>
            <th>Nom</th>
            <th>Prenom</th>
            </thead>
            <tbody>
            <?php
            foreach ($res as $row) {
                ?>

                <tr>
                    <td><?php echo ($row['pid']) ?></td>
                    <td><?php echo $row['nom'] ?></td>
                    <td><?php echo $row['prenom'] ?></td>
                </tr>

                <?php
            };
            ?>


            </tbody>
        </table>

    </div>
        <?php
}

$SQL = "SELECT * FROM itypes";
$res = $db->query($SQL);
if ($res->rowCount() == 0)
    echo error("La liste est vide");
else {
    ?>
    <h2>Gestions des itypes</h2>
    <div class="row">

        <table class="table table-striped table-bordered table-hover">
            <thead>
            <th>Itypes</th>
            </thead>
            <tbody>
            <?php
            foreach ($res as $row) {
                ?>

                <tr>
                    <td><?php echo ($row['tid']) ?></td>
                    <td><?php echo ($row['nom']) ?></td>

                </tr>

                <?php
            };
            ?>


            </tbody>
        </table>

    </div>


    <?php

}
?>
<h3>Ajout d'une identification à une personne</h3>
<form action="ajout.php" method="post">
    <table>
        <select name="pid" >
            <option value="">-------------------- Pid -----------------</option>
            <?php
            $SQL = "SELECT * FROM personnes";
            $res = $db->query($SQL);
            if ($res->rowCount() == 0)
                echo error("La liste est vide");
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
        </select><br>
        <select name="tid" >
            <option value="">-------------------- Tid -----------------</option>
            <?php
            $SQL = "SELECT * FROM itypes";
            $res = $db->query($SQL);
            if ($res->rowCount() == 0)
                echo error("La liste est vide");
            else {
                ?>
                <div class="row">
                    <?php
                    foreach ($res as $row) {
                        ?>
                        <option value="<?php echo ($row['tid']) ?>"><?php echo ($row['tid']) ?> | <?php echo ($row['nom']) ?></option>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </select>
        <tr><td>Valeur</td><td><input type="text" name="valeur" /></td></tr>
    </table>
    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
</form>