<?php
$title="Ajouts d'un utilisateur";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");

if($idm->getRole() == "admin"){




//Récupération des données
if (!isset($_POST['login']) || !isset($_POST['mdp']) || !isset($_POST['mdp2'])|| !isset($_POST['role'])) {
    include("aj_form.php");
} else {

    $nom = $_POST['login'];
    $mdp = $_POST['mdp'];
    $mdp2 = $_POST['mdp2'];
    $role = $_POST['role'];

// Vérification
    if ($nom == "" || ($mdp != $mdp2) || $role == "" ) {
        error("Erreurs");
        include("aj_form.php");
    }
    else {// connexion a la BD
        $passwordFunction =
            function ($s) {
                return password_hash($s, PASSWORD_DEFAULT);
            };

        $clearData = $passwordFunction($mdp);

        $nom =  htmlspecialchars($nom);
        if(strlen($nom) > 30){
            error("Champs trop long, Max : 30 caractères");
        }
        else {
            $SQL = "INSERT INTO users VALUES (DEFAULT, ?,?,?)";
            $st = $db->prepare($SQL);
            $res = $st->execute(array($nom, $clearData, $role));
            if (!$res) // ou if ($st->rowCount() ==0)
                error("Erreur d’ajout");
            else succes("L'ajout a été effectué");
        }
    }
}
echo "<a href=\"..\utilisateur.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>