<?php
$title="Gestion des personnes";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){

    if (!isset($_GET["eid"])) {

    } else {
        ?><h1><?php echo $title; ?></h1>
        <p>
            La liste des personnes inscrite, mais elle n'a pas participé à l'événement.
        </p>
        <?php
        $id = $_GET["eid"];


        $SQL = "SELECT * FROM inscriptions INNER JOIN  evenements ON evenements.eid = inscriptions.eid INNER JOIN personnes ON personnes.pid = inscriptions.pid WHERE inscriptions.eid =:id";
        $st = $db->prepare($SQL);
        $st->execute(array('id' => "$id"));

        if ($st->rowCount() == 0)
            error("La liste est vide");
        else {
            echo "<h2>Liste des personnes de l'event eid :  " . $id . "</h2>" ;?>
            <div class="row">

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <th>Pid</th>
                    <th>Nom</th>
                    <th>Prenom</th>

                    </thead>
                    <tbody>
                    <?php
                    foreach ($st as $row) {
                        ?>

                        <tr>
                            <td><?php echo ($row['pid']) ?></td>
                            <td><?php echo $row['nom'] ?></td>
                            <td><?php echo $row['prenom'] ?></td>

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
INNER JOIN personnes ON personnes.pid = inscriptions.pid
 WHERE inscriptions.eid = :id AND inscriptions.pid NOT IN (SELECT pid FROM participations WHERE eid =:id )";
            $st = $db->prepare($SQL);
            $st->execute(array('id' => "$id",'id' => "$id"));
            ?>
            <h1><?php echo $title; ?></h1>
            <p>
                La liste des personnes inscrite, mais elle n'a pas participé à l'événement.
            </p><?php
            if ($st->rowCount() == 0)
                error("La liste est vide");
            else {

                echo "<h2>Liste des événements de eid " . $id . "</h2>" ;?>
                <div class="row">

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <th>Pid</th>
                        <th>Nom</th>
                        <th>Prenom</th>

                        </thead>
                        <tbody>
                        <?php
                        foreach ($st as $row) {
                            ?>

                            <tr>
                                <td><?php echo ($row['pid']) ?></td>
                                <td><?php echo $row['nom'] ?></td>
                                <td><?php echo $row['prenom'] ?></td>

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
    echo "<a href=\"personne.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}


include($path."/footer.php");
?>


