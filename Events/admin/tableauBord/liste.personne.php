<?php
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$SQL = "SELECT COUNT(personnes.pid) AS total , personnes.pid, personnes.nom FROM participations  INNER JOIN personnes ON participations.pid = personnes.pid  GROUP BY personnes.pid ORDER BY personnes.nom  ";
$res = $db->query($SQL);
if ($res->rowCount() == 0)
    echo error("La liste est vide");
else {
    ?>
    <h1>Affichage par personnes GROUPBY</h1>
    <div class="row">

        <table class="table table-striped table-bordered table-hover">
            <thead>
            <th>Pid</th>
            <th>Personnes</th>
            <th>Participations</th>
            </thead>
            <tbody>
            <?php
            foreach ($res as $row) {
                ?>

                <tr>
                    <td><?php echo $row['pid'] ?></td>
                    <td><?php echo $row['nom'] ?></td>
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
$SQL = "SELECT * FROM participations  INNER JOIN personnes ON participations.pid = personnes.pid ORDER BY nom";
$res = $db->query($SQL);
if ($res->rowCount() == 0)
    echo "<P>La liste est vide";
else {
    ?>
    <h1>Affichage par personne</h1>
    <div class="row">

        <table class="table table-striped table-bordered table-hover">
            <thead>
            <th>Pid</th>
            <th>Personne</th>

            </thead>
            <tbody>
            <?php
            foreach ($res as $row) {
                ?>

                <tr>

                    <td><?php echo $row['pid'] ?></td>
                    <td><?php echo $row['nom'] ?></td>




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