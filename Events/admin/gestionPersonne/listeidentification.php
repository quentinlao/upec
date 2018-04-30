<?php
$title="Gestion des types d'identifications";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){


    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (!isset($_GET["pid"])) {

    } else {

        $id = $_GET["pid"];


        $SQL = "SELECT * FROM identifications WHERE pid=:id";
        $st = $db->prepare($SQL);
        $st->execute(array('id' => "$id"));

        if ($st->rowCount() == 0)
            error("La liste est vide");
        else {

            ?>
            <h1><?php echo $title; ?></h1>
            <div class="row">

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <th>Pid</th>
                    <th>Tid</th>
                    <th>Valeur</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>

                    </thead>
                    <tbody>
                    <?php
                    foreach ($st as $row) {
                        ?>

                        <tr>
                            <td><?php echo($row['pid']) ?></td>
                            <td><?php echo $row['tid'] ?></td>
                            <td><?php echo $row['valeur'] ?></td>
                            <td>
                                <a href="identification/mod.php?pid=<?php echo $row['pid']; ?>&tid=<?php echo $row['tid']; ?>">
                                    <button type="button" class="btn btn-lg btn-primary">Modifier</button>
                                </a></td>
                            <td>
                                <a href="identification/sup.php?pid=<?php echo $row['pid']; ?>&tid=<?php echo $row['tid']; ?>">
                                    <button type="button" class="btn btn-lg btn-danger">Supprimer</button>
                                </a></td>

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
    echo "<a href=\"liste.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}


include($path."/footer.php");
?>


