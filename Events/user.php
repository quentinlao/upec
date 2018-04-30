
<?php
$title="Users";
$path = "";
require($path."auth/EtreAuthentifie.php");
include($path."header.php");


//$racine = str_replace('\\', '/', realpath($racine));




    ?>
    <h1>Pour les utilisateurs</h1>
    <a href="user/pointage.php">Pointage pour un événement</a><br>
    <a href="user/affichage.php">Affichage personnes inscrites</a><br>
    <a href="user/batch.php">Soumission en batch pour plusieurs participations pour 1 personne</a><br>
<a href="user/batch2.php">Soumission en batch pour une participation pour plusieurs personnes</a><br>
<h2>Rappel</h2>
<p>

<br><br>
    <b class="red">— Pointage d’une personne </b> pour un événement (vérification de son identification). Pour les événements ouverts les personnes inexistantes sont rajoutées dans le système d’information (en
    remplissant la table personnes et éventuellement les tables itypes et identifications).<br><br>
        <b class="red">  — Affichage </b> de la liste de toutes les personnes inscrites pour un événement (avec toutes leurs
    identifications).<br><br>
            <b class="red">— Soumission </b> en batch (groupée) de plusieurs participations.
</p>

    <?php







include("footer.php");