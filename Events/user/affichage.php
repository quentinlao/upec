<?php
require("../auth/EtreAuthentifie.php");


$title = 'Affichage personnes inscrites';
$path = "../";

include($path . "header.php");
$SQL = "SELECT * FROM inscriptions INNER JOIN personnes ON inscriptions.pid = personnes.pid INNER JOIN evenements ON evenements.eid = inscriptions.eid INNER JOIN identifications ON identifications.pid = personnes.pid ";
$res = $db->query($SQL);
if ($res->rowCount() == 0){
    echo error("<p>La liste est vide</p>");
}
else {
    ?>
    <h1><?php echo $title; ?></h1>
    <div class="row">

        <table class="table table-striped table-bordered table-hover">
            <thead>
            <th>Eid</th>
            <th>Intitule</th>
            <th>Personne</th>
            <th>Identification</th>


            </thead>
            <tbody>
            <?php

            foreach ($res as $row) {
                ?>

                <tr>
                    <td><?php echo($row['eid']) ?></td>
                    <td><?php echo $row['intitule'] ?></td>
                    <td><?php echo $row['nom'] ?></td>
                    <td><?php echo $row['valeur'] ?></td>


                </tr>

                <?php
            }
            ?>


            </tbody>
        </table>

    </div>

    <h2>Rappel</h2>
    <br>
    <p>
        <b class="red">— Affichage de la liste</b> de toutes les personnes inscrites pour un événement (avec toutes leurs
        identifications).
    </p>
    <?php
}

echo "<a href=\"..\user.php\">Revenir à la page de départ.</a>";
include($path . "/footer.php");


?>