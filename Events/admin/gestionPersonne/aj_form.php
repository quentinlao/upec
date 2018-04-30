<?php
/*
session_start();
for ($i = 0; $i <= 64; $i++) {
    $token = openssl_random_pseudo_bytes($i);
}
$name="CSRFGuard_".mt_rand(0,mt_getrandmax());
$_SESSION['nom_formulaire'.$name] = $token;
echo "<input type='hidden' name='CSRFName' value='$name' />
<input type='hidden' name='CSRFToken' value='$token' />";
$name = $_POST['CSRFName'];

if(isset($_SESSION['nom_formulaire'.$name])){
    $token = $_SESSION['nom_formulaire'.$name];
    unset ($_SESSION['nom_formulaire'.$name]);
    $token_recu = $_POST['CSRFToken'];
    $result = hash_equals($token, $token_recu);
}else {
    $result = false;
}*/
?>
<h1>Ajouter une personne</h1>
<form action="ajout.php" method="post">
    <table>
        <tr><td>Nom</td><td><input type="text" name="nom" /></td></tr>
        <tr><td>Prenom</td><td><input type="text" name="prenom" /></td></tr>
    </table>
    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
</form>