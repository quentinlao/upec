
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title><?= "Events - ". $title??"" ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo $path ;?>bootstrap/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico|Quicksand" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
-->
    <!-- Optional theme -->
    <script src="http://code.jquery.com/jquery-1.12.0.js"></script>
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>
<!--
    <link rel="stylesheet" type="text/css" href="bootstrap/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <script src="bootstrap/js/bootstrap.min.js"></script> -->
</head>

<body>

<header>
    <?php include($path ."error.php");?>
    <div id="bar1">
        <div class="container">
            <div id="welcome"><p>Bienvenue sur le site de Events - Information</p></div>
            <div id="version"><p>Version crée pour le projet de l'upec</p></div>
        </div>
    </div>
    <div id="bar2">
        <div class="container">
            <a href="<?php echo $path ;?>index.php"><div id="logo"></div></a>
            <div id="hello">


<?php

if( $idm->getIdentity()) {
    if($idm->getRole()== "admin"){
        echo " <div id=\"adminImg\"></div>";
    }
    else{
        echo "<div id=\"userImg\"></div>";
   }
    echo "Nom : <span class=\"forte\">" . $idm->getIdentity()."</span> | Id : <span class=\"forte\">". $idm->getUid() ."</span><br>Role : <span class=\"forte\">".$idm->getRole(). "</span>";
     ?>
    <a class ="logout" href="<?php echo $path ;?>logout.php"></a>
    <?php

}
else{
    ?>

    <div id="invite"><p>Bonjour, <span class="forte">Invité</span></p></div>
                <?php
}

?>
            </div>
        </div>
    </div>

    <div id="bar3">
        <div class="container">
            <ul>

                <li ><a href="<?php echo $path;?>index.php">Accueil</a></li>
                <li ><a href="<?php echo $path;?>administration.php">Administrations</a></li>

                <li ><a href="<?php echo $path;?>user.php">Utilisateurs</a></li>
                <li ><a href="<?php echo $path;?>adduser.php">S'enregistrer</a></li>
                <?php if($idm->getRole()== "admin" || $idm->getRole()== "user" ){ ?>
                    <li ><a href="<?php echo $path;?>logout.php">Deconnexion</a></li>
                    <?php
                }
                else{?>
                    <li ><a href="<?php echo $path;?>login.php">Connexion</a></li>
                    <?php
                }
             ?>


            </ul>
        </div>
    </div>
</header>


<div id="bckContain">
<div class="container">
