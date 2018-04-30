<?php
$title="Modifier";
$path = "../../";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");
if($idm->getRole() == "admin"){

?>
<h1>Attention modification mot de passe</h1>
    <p>
        Attention le changement est définitif.
    </p>
<?php
if (!isset($_GET["uid"])) {

}else {

        $id = $_GET["uid"];


        $SQL = "SELECT * FROM users WHERE uid=:id";
        $st = $db->prepare($SQL);
        $st->execute(array('id'=>"$id"));
        $res = $st->fetch();
        $login = $res['login'];



        if ($st->rowCount() == 0) {
            error("Erreur de modification");
        } else if ( !isset($_POST['mdp']) || !isset($_POST['mdp2'])) {
            include("mod_form.php");
        } else {

            $mdp = $_POST['mdp'];
            $mdp2 = $_POST['mdp2'];
            if ( $mdp != $mdp2 ) {
                include("mod_form.php");
            } else {
                $passwordFunction =
                    function ($s) {
                        return password_hash($s, PASSWORD_DEFAULT);
                    };

                $clearData = $passwordFunction($mdp);

                $SQL = "UPDATE users SET  mdp=? WHERE uid=? ";
                $st = $db->prepare($SQL);
                $res = $st->execute(array( $clearData, $id));
                if (!$res) // ou if ($st->rowCount() ==0)
                    error("Erreur de modification");
                else
                   succes("La modification a été effectuée");
            }
            $db = null;
        }

}
echo "<a href=\"..\utilisateur.php\">Revenir à la page de départ.</a>";

}
else{
    error("Tu n'es pas un administrateur");
}

include($path."/footer.php");
?>