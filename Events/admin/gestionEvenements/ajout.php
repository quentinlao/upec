<?php
$title="Ajouts";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
if($idm->getRole() == "admin"){


    include($path."header.php");

//Récupération des données
if ( !isset($_POST['intitule']) || !isset($_POST['description']) || !isset($_POST['dateDebut']) || !isset($_POST['dateFin']) || !isset($_POST['type']) || !isset($_POST['gestion']) )  {
    include("aj_form.php");

} else {
    $intitule = $_POST['intitule'];
    $description = $_POST['description'];
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];
    $type = $_POST['type'];
    $cid = $_POST['gestion'];

// Vérification
    if ($intitule == "" || $description == "" || $dateDebut == "" || $dateFin == "" || $type =="" || $cid == "")  {
        include("aj_form.php");
    }
    else if($dateDebut > $dateFin){
        include("aj_form.php");
    }
    else {// connexion a la BD

        $intitule =  htmlspecialchars($intitule);
        $description = htmlspecialchars($description);

        if(strlen($intitule) > 100 ){
            error("Champs trop long, Max : 100 caractères");
        }
        else {
           // $db->query("SET foreign_key_checks = 0");

            $req = $db->prepare("INSERT INTO evenements(intitule,description,dateDebut,dateFin,type,cid) VALUES (:intitule,:description,:dateDebut,:dateFin,:type,:cid)");
            $req->execute(array(
                'intitule' => $intitule,
                'description' => $description,
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
                'type' => $type,
                'cid' => $cid,


            ));
          //  $db->query("SET foreign_key_checks = 1");
            succes("Ajout de l'événement");
            // $st = $db->prepare($SQL);
               // $res = $st->execute(array( $intitule, $description,$dateDebut,$dateFin,$type,$cid));

           /*     if (!$res) // ou if ($st->rowCount() ==0)
                    echo "Erreur d’ajout";
                else echo "L'ajout a été effectué";

                $st=null;*/


        }
    }
}

echo "<a href=\"../evenements.php\">Revenir à la page de départ.</a>";
include($path."/footer.php");

}
else{
    error("Tu n'es pas un administrateur");
}

?>