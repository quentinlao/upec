<h1>Modifier un événement</h1>
<form action="" method="post">
    <table>
        <tr><td>Intitule</td><td><input type="text" name="intitule" value="<?php
                echo htmlspecialchars($intitule);?>"/></td></tr>
        <tr><td>Description</td><td><textarea type="text" name="description" ><?php
                    echo htmlspecialchars($description);?></textarea></td></tr>
        <tr><td>Date Debut</td><td><input type="date" name="dateDebut" value="<?php
                echo htmlspecialchars($dateDebut);?>"/></td></tr>
        <tr><td>Date Fin</td><td><input type="date" name="dateFin" value="<?php
                echo htmlspecialchars($dateFin);?>"/></td></tr>
        <tr><td>Type</td><td><input type="radio" name="type" value="ouvert" />Ouvert <input type="radio" name="type" value="ferme" /> Fermé</td></tr>
        <tr><td> <select name="gestion">
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
            </td>
        </tr>
    </table>

    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
</form>