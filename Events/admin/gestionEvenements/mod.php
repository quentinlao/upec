<?php
$title="Modifier";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
if($idm->getRole() == "admin"){


    include($path."header.php");

?>
<h1>Modifier une eid</h1>
    <form action="mod.php" method="get">
        <table>
            <tr><td>eid</td><td><input type="number" name="eid" /></td></tr>

        </table>
        <input type="submit" value="Envoyer" />
    </form>
<?php
if (!isset($_GET["eid"])) {

}else {

        $id = $_GET["eid"];


        $SQL = "SELECT * FROM evenements  WHERE eid=:id";
        $st = $db->prepare($SQL);
        $st->execute(array('id'=>"$id"));
        $res = $st->fetch();
        $intitule = $res['intitule'];
        $description = $res['description'];
        $dateDebut = $res['dateDebut'];
        $time = strtotime($dateDebut);
        $dateDebut = date("Y-m-d", $time);
        $dateFin = $res['dateFin'];
        $time = strtotime($dateFin);
        $dateFin = date("Y-m-d", $time);
        $type = $res['type'];
        $cid = $res['cid'];
        if ($st->rowCount() == 0) {
            error("Erreur de modification");
        } else if (!isset($_POST['intitule']) || !isset($_POST['dateDebut']) || !isset($_POST['dateFin']) || !isset($_POST['type']) || !isset($_POST['gestion'])) {
            include("mod_form.php");
        } else {
            $intitule = $_POST['intitule'];
            $description =  $_POST['description'];
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            $type = $_POST['type'];
            $cid = $_POST['gestion'];


            $intitule =  htmlspecialchars($_POST['intitule']);
            $type = htmlspecialchars($_POST['type']);

            if ($intitule == "" || $type == "" ) {
                include("mod_form.php");
            } else {
                if(strlen($intitule) > 100 ){
                    error("Champs trop long, Max : 100 caractères");
                }
                else {

                    $SQL = "UPDATE evenements SET intitule=?, description=? , dateDebut=?,dateFin=?,type=?,cid=? WHERE eid=? ";
                    $st = $db->prepare($SQL);
                    $res = $st->execute(array($intitule, $description,$dateDebut,$dateFin,$type,$cid, $id));
                    if (!$res) // ou if ($st->rowCount() ==0)
                        error("Erreur de modification");
                    else
                        succes("La modification a été effectuée");

                }
            }
            $db = null;
        }

}
echo "<a href=\"../evenements.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>