<?php
$title="Ajouts";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){


//Récupération des données
if (!isset($_POST['nom']) || !isset($_POST['prenom'])) {
    include("aj_form.php");
} else {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

// Vérification
    if ($nom == "" || $prenom == "" ) {
        include("aj_form.php");
    }
    else {// connexion a la BD
        $nom =  htmlspecialchars($nom);
        $prenom = htmlspecialchars($prenom);
        if(strlen($nom) > 30 || strlen($prenom) > 30){
            error("Champs trop long, Max : 30 caractères");
        }
        else {
            $SQL = "INSERT INTO personnes VALUES (DEFAULT,?,?)";
            $st = $db->prepare($SQL);
            $res = $st->execute(array($nom, $prenom));
            if (!$res) // ou if ($st->rowCount() ==0)
                error("Erreur d’ajout");
            else succes("L'ajout a été effectué");
        }
    }
}
echo "<a href=\"..\personnes.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>