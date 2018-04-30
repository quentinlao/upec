<?php
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$SQL = "SELECT COUNT(participations.date) AS total , participations.date FROM participations   GROUP BY participations.date ORDER BY participations.date ";
$res = $db->query($SQL);
if ($res->rowCount() == 0)
    echo error("La liste est vide");
else {
    ?>
    <h1>Affichage par date GROUPBY</h1>
    <div class="row">

        <table class="table table-striped table-bordered table-hover">
            <thead>
            <th>Date</th>

            <th>Participations</th>
            </thead>
            <tbody>
            <?php
            foreach ($res as $row) {
                ?>

                <tr>
                    <td><?php echo $row['date'] ?></td>

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
$SQL = "SELECT * FROM participations ORDER BY date";
$res = $db->query($SQL);
if ($res->rowCount() == 0)
    echo "<P>La liste est vide";
else {
    ?>
    <h1>Affichage par date</h1>
    <div class="row">

        <table class="table table-striped table-bordered table-hover">
            <thead>

            <th>Date</th>

            </thead>
            <tbody>
            <?php
            foreach ($res as $row) {
                ?>

                <tr>

                    <td><?php echo $row['date'] ?></td>


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