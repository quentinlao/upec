<?php
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$SQL = "SELECT COUNT(participations.eid) AS total ,participations.eid, evenements.intitule FROM participations INNER JOIN evenements ON evenements.eid = participations.eid  GROUP BY participations.eid ORDER BY intitule ";
$res = $db->query($SQL);
if ($res->rowCount() == 0)
echo error("La liste est vide");
    else {
    ?>
<h1>Affichage par événements GROUPBY</h1>
<div class="row">

    <table class="table table-striped table-bordered table-hover">
        <thead>
        <th>Eid</th>
        <th>Evenements</th>
        <th>Participants</th>
        </thead>
        <tbody>
        <?php
        foreach ($res as $row) {
            ?>

            <tr>
                <td><?php echo $row['eid'] ?></td>
                <td><?php echo $row['intitule'] ?></td>
                <td><?php echo $row['total'] ?></td>

            </tr>

            <?php
        };
        ?>


        </tbody>
    </table>

</div>


<?php
    }

$SQL = "SELECT * FROM participations INNER JOIN evenements ON evenements.eid = participations.eid ORDER BY intitule";
$res = $db->query($SQL);
if ($res->rowCount() == 0)
echo error("La liste est vide");
else {
    ?>
    <h1>Affichage par événements</h1>
    <div class="row">

        <table class="table table-striped table-bordered table-hover">
            <thead>
            <th>Eid</th>
            <th>Evenements</th>
            </thead>
            <tbody>
            <?php
            foreach ($res as $row) {
                ?>

                <tr>
                    <td><?php echo($row['eid']) ?></td>
                    <td><?php echo $row['intitule'] ?></td>


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
