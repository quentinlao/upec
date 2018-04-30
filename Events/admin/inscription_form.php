<?php

?>
<h1><?php echo $title;?></h1>


<?php
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$SQL = "SELECT * FROM inscriptions";
$res = $db->query($SQL);
if ($res->rowCount() == 0)
    echo error("La liste est vide");
else {
    ?>
    <h2>Gestions des inscriptions</h2>
    <div class="row">

        <table class="table table-striped table-bordered table-hover">
            <thead>
            <th>Pid</th>
            <th>Eid</th>
            <th>Uid</th>
            </thead>
            <tbody>
            <?php
            foreach ($res as $row) {
                ?>

                <tr>
                    <td><?php echo ($row['pid']) ?></td>
                    <td><?php echo $row['eid'] ?></td>
                    <td><?php echo $row['uid'] ?></td>
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


<h3>Ajout d'une inscription</h3>
<form action="inscription.php" method="post">
    <table>
        <tr><td>
        <select name="pid">
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
        </select>
            </td></tr>

        <tr><td>
                <select name="eid">
                    <option value="">-------------------- Eid -----------------</option>
                    <?php
                    $SQL = "SELECT * FROM evenements";
                    $res = $db->query($SQL);
                    if ($res->rowCount() == 0)
                        echo "La liste est vide";
                    else {
                        ?>
                        <div class="row">
                            <?php
                            foreach ($res as $row) {
                                ?>
                                <option value="<?php echo ($row['eid']) ?>"><?php echo ($row['eid']) ?> | <?php echo ($row['intitule']) ?> | <?php echo ($row['type']) ?></option>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </select>
            </td></tr>
        <tr><td>
                <select name="uid" >
                    <option value="">-------------------- Uid -----------------</option>
                    <?php
                    $SQL = "SELECT * FROM users";
                    $res = $db->query($SQL);
                    if ($res->rowCount() == 0)
                        echo error("La liste est vide");
                    else {
                        ?>
                        <div class="row">
                            <?php
                            foreach ($res as $row) {
                                ?>
                                <option value="<?php echo ($row['uid']) ?>"><?php echo ($row['uid']) ?> | <?php echo ($row['login']) ?></option>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </select>
            </td></tr>
    </table>
    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
</form>
