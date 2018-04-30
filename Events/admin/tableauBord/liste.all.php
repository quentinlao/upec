<?php
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$SQL = "SELECT * FROM participations INNER JOIN evenements ON evenements.eid = participations.eid INNER JOIN personnes ON participations.pid = personnes.pid ORDER BY intitule";
$res = $db->query($SQL);
if ($res->rowCount() == 0)
    echo "<P>La liste est vide";
else {
    ?>
    <h1>Affichage par all</h1>
    <div class="row">

        <table class="table table-striped table-bordered table-hover">
            <thead>
            <th>Eid</th>
            <th>Evenements</th>
            <th>Pid</th>
            <th>Personne</th>
            <th>Date</th>
            <th>Uid</th>
            </thead>
            <tbody>
            <?php
            foreach ($res as $row) {
                ?>

                <tr>
                    <td><?php echo($row['eid']) ?></td>
                    <td><?php echo $row['intitule'] ?></td>
                    <td><?php echo $row['pid'] ?></td>
                    <td><?php echo $row['nom'] ?></td>
                    <td><?php echo $row['date'] ?></td>
                    <td><?php echo $row['uid'] ?></td>



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