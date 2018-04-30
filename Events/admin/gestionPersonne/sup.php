<?php
$title="Supprimer une personne";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");

if($idm->getRole() == "admin"){


?>

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

        $SQL = "DELETE FROM personnes WHERE pid=:id";
        $st = $db->prepare($SQL);
        $res = $st->execute(array('id' => $id));
        succes("Supression");
        $db = null;
    }

    catch (PDOException $e){
        echo "Erreur SQL: " . $e->getMessage();
    }
}
echo "<a href=\"..\personnes.php\">Revenir à la page de départ.</a>";
?>
    <h2>Rappel</h2>
    <p>Action définitive.</p>
<?php
}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>