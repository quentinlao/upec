<?php
$title="Listes";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){

?>
    <h1>Affichage des personnes</h1>
    <p>—<b> Tableau de bord :</b> pour chaque personne affichage des événements auxquels elle a été inscrite,
        mais auxquels elle n’a pas participé.
    </p>
    <?php
    $SQL = "SELECT * FROM personnes";
    $res = $db->query($SQL);
    if ($res->rowCount() == 0)

        echo error("<p>La liste est vide</p>");
    else {
        ?>

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
                        <td><a href="afficheEvent.php?pid=<?php echo $row['pid'];?>"> <?php echo ($row['pid']) ?></a></td>
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
    ?>
    <h1>Affichage des personnes où il y a des absents</h1>
    <p>—<b> Tableau de bord :</b> pour chaque personne affichage des événements auxquels elle a été inscrite,
        mais auxquels elle n’a pas participé.
    </p>
    <?php
$SQL = "SELECT * FROM inscriptions
        INNER JOIN personnes ON personnes.pid = inscriptions.pid 
        INNER JOIN evenements ON inscriptions.eid = evenements.eid
        WHERE inscriptions.pid NOT IN (SELECT participations.pid FROM participations WHERE participations.pid = inscriptions.pid AND inscriptions.eid = participations.eid)  ";

$res = $db->query($SQL);
if ($res->rowCount() == 0)
    echo error("La liste est vide");


else {
        ?>

        <div class="row">

            <table class="table table-striped table-bordered table-hover">
                <thead>
                <th>Pid</th>
                <th>Personne</th>
                <th>Date</th>
                <th>Evenements</th>
                </thead>
                <tbody>
                <?php
                foreach ($res as $row) {
                    ?>

                    <tr>
                        <td><?php echo $row['pid'] ?></td>
                        <td><?php echo $row['nom'] ?></td>

                        <td><b>Participation </b><b>Debut :</b>   <?php echo $row['dateDebut'] ?> <br>  <b>Fin :</b>  <?php echo $row['dateFin'] ?></td>
                        <td><b>Absent :</b> <?php echo $row['intitule'] ?></td>


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
echo "<a href=\"../tableauBord.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}
include($path."/footer.php");
?>
