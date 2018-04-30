
<?php
$title="Accueil";
$path = "";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");


//$racine = str_replace('\\', '/', realpath($racine));

if($idm->getRole() == "admin"){




?>


    <div id="info">
        <h1>Gestion des administrateurs</h1>
        <div class="row">
            <div class="col-sm-6 col-xs-3">
                <div class="thumbnail">
                    <img src="bootstrap/css/img/events.png" alt="">
                    <div class="caption">
                        <h3>Evènements</h3>
                        <div class="center">
                      <?php  $SQL = "SELECT COUNT(eid)  AS total FROM evenements";
                        $res = $db->query($SQL);
                        $donnees = $res->fetch();
                        if ($res->rowCount() == 0)
                        echo "La liste est vide";
                            else {
                            echo "<p><div class='circle'>" . $donnees['total'] . "</div> Evenements disponibles</p>";
                            }
                                ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xs-3">
                <div class="thumbnail">
                    <img src="bootstrap/css/img/part.png" alt=""><div class="caption">
                        <h3>Participants</h3>
                        <div class="center">
                            <?php  $SQL = "SELECT COUNT(ptid)  AS total FROM participations";
                            $res = $db->query($SQL);
                            $donnees = $res->fetch();
                            if ($res->rowCount() == 0)
                                echo "La liste est vide";
                            else {
                                echo "<p><div class='circle'>" . $donnees['total'] . " </div>Participations disponibles</p>";
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xs-3">
                <div class="thumbnail">
                    <img src="bootstrap/css/img/id.png" alt="">  <div class="caption">
                        <h3>Identifications</h3>
                        <div class="center">
                            <?php  $SQL = "SELECT COUNT(pid)  AS total FROM identifications";
                            $res = $db->query($SQL);
                            $donnees = $res->fetch();
                            if ($res->rowCount() == 0)
                                echo "La liste est vide";
                            else {
                                echo "<p><div class='circle'>" . $donnees['total'] . " </div>Identifications disponibles</p>";
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xs-3">
                <div class="thumbnail">
                    <img src="bootstrap/css/img/info.png" alt="">  <div class="caption">
                        <h3>Personnes</h3>
                        <div class="center">
                            <?php  $SQL = "SELECT COUNT(pid)  AS total FROM personnes";
                            $res = $db->query($SQL);
                            $donnees = $res->fetch();
                            if ($res->rowCount() == 0)
                                echo "La liste est vide";
                            else {
                                echo "<p><div class='circle'>" . $donnees['total'] . " </div>Personnes disponibles</p>";
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <h1>Manipulation</h1>
<a href="admin/utilisateur.php">Gestion des utilisateurs</a><br>
<a href="admin/personnes.php">Gestion des personnes</a><br>
<a href="admin/identification.php">Gestion des types d'identification</a><br>

<a href="admin/evenements.php">Gestion des évènements</a><br>

<a href="admin/tableauBord.php">Tableau de bord </a><br>
    <h3>Pour nos test</h3>
    <a href="admin/inscription.php">Inscription </a><br>
    <a href="admin/itype.php">Création d'un itype </a><br>
    <a href="admin/categorie.php">Création d'une categorie </a><br>
    <h2>Rappel</h2><br>
    <p>
       <b class="red"> — Gestion des utilisateurs : </b>liste, ajout, modification mdp.<br>
        <b class="red"> — Gestion des personnes : </b>liste, ajout, suppression, modification.<br>
        <b class="red">— Gestion des types d’identification :</b> liste, ajout, modification, suppression.<br>
        <b class="red"> — Pour chaque personne :</b> liste, ajout, suppression, modification (des valeurs) des identifications.<br>
        <b class="red">  — Gestion des événements :</b> liste, ajout, suppression, modification.<br>
        <b class="red">— Tableau de bord :</b> affichage des participations aux événements (par événement, par personne,
        par date).<br>
        <b class="red"> — Tableau de bord :</b> pour chaque personne affichage des événements auxquels elle a été inscrite,
        mais auxquels elle n’a pas participé.<br>
        <b class="red"> — Tableau de bord :</b> pour chaque événement affichage des personnes inscrites qui n’on pas participé
        à l’événement.<br>
        <b class="red"> — Inscription :</b> Inscription d'une personne à un événement fermé (POUR LES TEST)
    </p>
<?php





}
else{
    error("Tu n'es pas un administrateur");
}
include("footer.php");