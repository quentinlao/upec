
<h1>Pointage d'une personne</h1>

<form action="pointage.php" method="post">
    <table>
         <div class="form-group"><label class="red bold">Choisir un événement : </label>
                    <select class="form-control" name="evenement">
                    <option value="">-------------------- Evenement -----------------</option>
                    <?php
                    $SQL = "SELECT * FROM evenements";
                    $res = $db->query($SQL);
                    if ($res->rowCount() == 0)
                        echo "<P>La liste est vide";
                    else {
                        ?>

                        <div class="row">



                            <?php
                            foreach ($res as $row) {
                                ?>

                                <option value="<?php echo ($row['eid']) ?>"><?php echo ($row['eid']) ?> | <?php echo ($row['intitule']) ?> | <?php echo ($row['type']) ?> ||  <?php  echo "Du ". ($row['dateDebut']) . " au " . ($row['dateFin'])?> </option>
                                <?php
                            };
                            ?>


                        </div>



                    </select><label class="red bold">Pour la personne : </label>



                     <tr><td>Nom</td><td><input type="text" name="nom" /></td></tr>
                     <tr><td>Prenom</td><td><input type="text" name="prenom" /></td></tr>
                    <tr><td>Date</td><td><input type="date" name="date" /></td></tr>




                     <?php
                     }
                     $db = null;

                 ?>





         </div>

    </table>

    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
