<?php
$title="Listes";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
?>
<h1>Affichage par events</h1>
— <b>Tableau de bord :</b> pour chaque événement affichage des personnes inscrites qui n’ont pas participé
à l’événement.<br>
<?php
if($idm->getRole() == "admin"){

    $SQL = "SELECT * FROM evenements INNER JOIN categories ON evenements.cid = categories.cid ";
    $res = $db->query($SQL);
    if ($res->rowCount() == 0)
        echo error("La liste est vide");
    else {
        ?>

        <h1>Gestions des évènements</h1>
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

                </thead>
                <tbody>
                <?php
                foreach ($res as $row) {
                    ?>

                    <tr>
                        <td><a href="afficheEventInscrit.php?eid=<?php echo $row['eid'];?>"> <?php echo ($row['eid']) ?></a></td>
                        <td><?php echo $row['intitule'] ?></td>
                        <td><?php echo $row['description'] ?></td>
                        <td><?php echo $row['dateDebut'] ?></td>
                        <td><?php echo $row['dateFin'] ?></td>
                        <td><?php echo $row['type'] ?></td>
                        <td><?php echo $row['cid'] ?></td>
                        <td><?php echo $row['nom'] ?></td>

                    </tr>

                    <?php
                };
                ?>








            </tbody>
        </table>

    </div>

    <h1>Affichage par événement</h1>
    — <b>Tableau de bord :</b> pour chaque événement affichage des personnes inscrites qui n’ont pas participé
    à l’événement.<br>
    <?php

    $SQL = "SELECT * FROM evenements
        INNER JOIN inscriptions ON evenements.eid = inscriptions.eid 
        INNER JOIN personnes ON inscriptions.pid = personnes.pid
        WHERE inscriptions.eid NOT IN (SELECT participations.eid FROM participations WHERE participations.pid = inscriptions.pid AND inscriptions.eid = participations.eid)";

    $res = $db->query($SQL);
    if ($res->rowCount() == 0)
        echo error("La liste est vide");
    else {
        ?>
        <div class="row">

            <table class="table table-striped table-bordered table-hover">
                <thead>
                <th>Eid</th>
                <th>Evenements</th>
                <th>Personne</th>
                <th>Date</th>
                </thead>
                <tbody>
                <?php
                foreach ($res as $row) {
                    ?>

                    <tr>
                        <td><?php echo $row['eid'] ?></td>
                        <td><b>Absent :</b> <?php echo $row['intitule'] ?></td>
                        <td><?php echo $row['nom'] ?></td>

                        <td><b>Debut :</b>   <?php echo $row['dateDebut'] ?> <br>  <b>Fin :</b>  <?php echo $row['dateFin'] ?></td>


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
}
echo "<a href=\"../tableauBord.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}
include($path."/footer.php");
?>
