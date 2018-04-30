<?php
$title="Listes";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");

if($idm->getRole() == "admin"){




$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $SQL = "SELECT * FROM users";
    $res = $db->query($SQL);
    if ($res->rowCount() == 0)
        echo "<P>La liste est vide";
    else {
?>
        <h1>Gestion des utilisateurs - Events</h1>
        <div class="row">

        <table class="table table-striped table-bordered table-hover">
        <thead>
        <th>Uid</th>
        <th>Login</th>
        <th>Role</th>
        <th>Modifier</th>
        </thead>
        <tbody>
        <?php
        foreach ($res as $row) {
            ?>

            <tr>
                <td><?php echo ($row['uid']) ?></td>
                <td><?php echo $row['login'] ?></td>
                <td><?php echo $row['role'] ?></td>
              <td><a href="mod.php?uid=<?php echo $row['uid'];?>">  <button type="button" class="btn btn-lg btn-primary">Modifier</button></a></td>


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

echo "<a href=\"..\utilisateur.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>


