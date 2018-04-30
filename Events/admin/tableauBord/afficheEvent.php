<?php
$title="Gestion des événements";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){

 if (!isset($_GET["pid"])) {

    } else {
        ?><h1><?php echo $title; ?></h1>
     <p>
         La liste des événements où la personne a été inscrite, et elle a  participé.
     </p>
     <?php
        $id = $_GET["pid"];


        $SQL = "SELECT * FROM inscriptions INNER JOIN evenements ON inscriptions.eid = evenements.eid WHERE inscriptions.pid =:id";
        $st = $db->prepare($SQL);
        $st->execute(array('id' => "$id"));

        if ($st->rowCount() == 0)
            error("La liste est vide");
        else {
         echo "<h2>Liste des événements de pid " . $id . "</h2>" ;?>
            <div class="row">

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <th>Eid</th>
                    <th>Intitule</th>
                    <th>Description</th>
                    <th>Date Debut</th>
                    <th>Date Fin</th>
                    <th>Type</th>

                    </thead>
                    <tbody>
                    <?php
                    foreach ($st as $row) {
                        ?>

                        <tr>
                            <td><?php echo ($row['eid']) ?></td>
                            <td><?php echo $row['intitule'] ?></td>
                            <td><?php echo $row['description'] ?></td>
                            <td><?php echo $row['dateDebut'] ?></td>
                            <td><?php echo $row['dateFin'] ?></td>
                            <td><?php echo $row['type'] ?></td>

                        </tr>

                        <?php
                    }
                    ?>


                    </tbody>
                </table>

            </div>


            <?php

            $SQL = "SELECT * FROM inscriptions
INNER JOIN evenements ON inscriptions.eid = evenements.eid
 WHERE inscriptions.pid =:id AND inscriptions.eid NOT IN (SELECT eid FROM participations WHERE pid =:id )";
            $st = $db->prepare($SQL);
            $st->execute(array('id' => "$id",'id' => "$id"));
    ?>
            <h1><?php echo $title; ?></h1>
            <p>
                La liste des événements où la personne a été inscrite, mais elle n'a pas participé.
            </p><?php
            if ($st->rowCount() == 0)
                error("La liste est vide");
            else {

               echo "<h2>Liste des événements de pid " . $id . "</h2>" ;?>
                <div class="row">

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <th>Eid</th>
                        <th>Intitule</th>
                        <th>Description</th>
                        <th>Date Debut</th>
                        <th>Date Fin</th>
                        <th>Type</th>

                        </thead>
                        <tbody>
                        <?php
                        foreach ($st as $row) {
                            ?>

                            <tr>
                                <td><?php echo ($row['eid']) ?></td>
                                <td><?php echo $row['intitule'] ?></td>
                                <td><?php echo $row['description'] ?></td>
                                <td><?php echo $row['dateDebut'] ?></td>
                                <td><?php echo $row['dateFin'] ?></td>
                                <td><?php echo $row['type'] ?></td>

                            </tr>

                            <?php
                        }
                        ?>


                        </tbody>
                    </table>

                </div>


                <?php
                $db = null;
            }
        }
    }
    echo "<a href=\"participe.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}


include($path."/footer.php");
?>


