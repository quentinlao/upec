
<?php
$title="Accueil";
$path = "";
require($path."auth/EtreInvite.php");
include($path."header.php");


//$racine = str_replace('\\', '/', realpath($racine));





?>
<div id="introduce">
    <h2>Gestion des <span class="red" >participations</span>, Events - information</h2>
    <p>Le but de ce projet est de créer un système d’information pour la gestion des participations aux évènements (regroupés en catégories) qui peuvent être de deux types : ouverts ou fermés.</p>
</div>
<div id="head">
 <h1>Les évènements à la une</h1>
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <a href="#"> <img src="bootstrap/css/img/concert.png" alt="Chania"></a>
            </div>

            <div class="item">
                <img src="bootstrap/css/img/lum.png" alt="Chania">
            </div>

            <div class="item">
                <img src="bootstrap/css/img/meeting.png" alt="Flower">
            </div>

        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only"></span>
        </a>

        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only"></span>
        </a>
    </div>

</div>
<div id="info">
    <h1>Informations</h1>
    <div class="row">
        <div class="col-sm-6 col-xs-3">
            <div class="thumbnail">
                <img src="bootstrap/css/img/events.png" alt="">
                <div class="caption">
                    <h3>Evènements</h3>
                    <p>Les <span class="red" >évènements </span> fermés ne peuvent pas accueillir des  <span class="red" >personnes </span>  non-inscrites, ce qui n’est pas le cas des évènements ouverts. </p>

                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xs-3">
            <div class="thumbnail">
                <img src="bootstrap/css/img/part.png" alt=""><div class="caption">
                    <h3>Participants</h3>
                    <p> Les <span class="red" >participants </span>
                        aux événements sont soumises à une inscription préalable par l’administrateur.</p>
                  </div>
            </div>
        </div>

        <div class="col-sm-6 col-xs-3">
            <div class="thumbnail">
                <img src="bootstrap/css/img/id.png" alt="">  <div class="caption">
                    <h3>Identifications</h3>
                    <p>Afin de participer à l’événement toute personne doit s’identifier à l’aide d’un moyen d’<span class="red" >identification </span>
                        (passeport, carte membre, invitation, etc).</p>
                 </div>
            </div>
        </div>

        <div class="col-sm-6 col-xs-3">
            <div class="thumbnail">
                <img src="bootstrap/css/img/info.png" alt="">  <div class="caption">
                    <h3>Informations</h3>
                    <p>Il est possible de<span class="red" > pointer plusieurs fois lors d’un événement</span>, cela pouvant correspondre, par exemple,
                        à des entrées/sorties.</p>
                     </div>
            </div>
        </div>
    </div>

</div>
<!-- Suppr -->
<div class="clear"></div>
</div>
</div>
<div id="head2">
    <div class="container">
    <h1 >Plan du site</h1>

        <img src="bootstrap/css/img/plan.png" alt="Chania">
    </div>
</div>
<div id="bckContain">
    <div class="container">

<div id="nous">
<h1>Le Projet</h1>

    <p>
        Le but de ce projet est de <span class="bold red">créer</span> un système d’information pour <span class="italic">la gestion des participations</span> aux événements (regroupés en catégories) qui peuvent être de deux types : ouverts ou fermés.<br>
        Les participations aux événements sont soumises à une inscription préalable par l’administrateur. <br>
        Les événements fermés ne peuvent pas accueillir des personnes non-inscrites, ce qui n’est pas le cas des événements ouverts.<br>
    <hr>
        Dans ce dernier cas la liste des inscrits est agrandi par conséquence (en rajoutant s’il le faut la nouvelle
        personne dans le système d’information). <br>
        Les informations concernant les événements et les participants se trouvent dans les tables evenements et participation.<br><br>
    <hr>
        Afin de participer à l’événement toute personne doit s’identifier à l’aide d’un moyen d’identification
        (passeport, carte membre, invitation, etc). Les informations concernant les personnes et les différents
        types d’identification se trouvent dans les tables personnes et itypes. <br>
        Le rattachement des moyens d’identification
        à la personne est fait par le biais de la table identifications. Les utilisateurs vérifient que le
        participant s’identifie avec un moyen valide. Pour les événements ouverts, un participant non-existant
        est rajouté dans la liste des personnes (ainsi que le moyen d’identification utilisé – pouvant être juste le
        nom de la personne).<br><br>
    <hr>
        Il est possible de pointer plusieurs fois lors d’un événement, cela pouvant correspondre, par exemple,
        à des entrées/sorties.<br><br>
    <hr>
        Les administrateurs du site peuvent gérer les listes des personnes, des utilisateurs et des événements.<br>
        Les utilisateurs du site peuvent effectuer les opérations de participation (pointage), ainsi que le rajout
        de nouvelles personnes à partir des événements ouverts.
    </p>
</div>
<?php
echo $path;





include("footer.php");