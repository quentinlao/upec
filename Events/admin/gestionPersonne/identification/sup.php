<?php
$title="Supprimer une personne";
$path = "../../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){



?>
    <h1>Supprimer une personne par pid</h1>
    <form action="sup.php" method="get">
        <table>
            <tr><td>pid</td><td><input type="number" name="pid" /></td></tr>

        </table>
        <input type="submit" value="Envoyer" />
    </form>
    <h1>Supprimer une personne </h1>
<?php
if (!isset($_GET["pid"])) {
    echo "<p>Erreur</p>\n";
}else if (!isset($_POST["supprimer"]) && !isset($_POST["annuler"]) ){
    include("del_form.php");
} else if (isset($_POST["annuler"])){
    echo "Operation annulee";
}else {
    require($path."/db_config.php");
    try {


        $id = $_GET["pid"];
        $tid = $_GET["tid"];
        $SQL = "DELETE FROM identifications WHERE pid=:id AND tid=:tid";
        $st = $db->prepare($SQL);
        $res = $st->execute(array('id' => $id,'tid'=>$tid));
        succes("Supression");
        $db = null;
    }

    catch (PDOException $e){
        echo "Erreur SQL: " . $e->getMessage();
    }
}echo "<a href=\"../liste.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>