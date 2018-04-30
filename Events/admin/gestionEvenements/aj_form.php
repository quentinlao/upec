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
<h1>Ajouter un évènement</h1>

<form action="ajout.php" method="post">
    <table>
        <tr><td>Intitule</td><td><input type="text" name="intitule" /></td></tr>
        <tr><td>Description</td><td><textarea  name="description" style="width: 500px; height: 200px;" ></textarea></td></tr>
        <tr><td>Date Debut</td><td><input type="date" name="dateDebut" /></td></tr>
        <tr><td>Date Fin</td><td><input type="date" name="dateFin" /></td></tr>
        <tr><td>Type</td><td><input type="radio" name="type" value="ouvert" />Ouvert <input type="radio" name="type" value="ferme" /> Fermé</td></tr>
        <div class="form-group"><label class="red bold">Choisir une catégorie : </label>

            <select name="gestion" class="form-control">
            <option value="">-------------------- Catégories -----------------</option>
                    <?php
                    $SQL = "SELECT * FROM categories";
                    $res = $db->query($SQL);
                    if ($res->rowCount() == 0)
                        echo "<P>La liste est vide";
                    else {
                        ?>

                        <div class="row">



                                <?php
                                foreach ($res as $row) {
                                    ?>

                            <option value="<?php echo ($row['cid']) ?>"><?php echo ($row['cid']) ?> | <?php echo ($row['nom']) ?></option>
                                    <?php
                                };
                                ?>


                        </div>


                        <?php
                        $db = null;
                    }
                    ?>

        </select>
        </div>

    </table>
    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
</form>