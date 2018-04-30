<?php
$title="Authentification";
include("header.php");

echo "<p class=\"error\">".($error??"")."</p>";
?>

<div id="form">
        <div class="container">

            <div id="boxId">




            <h1>Authentifiez-vous</h1>

                <form method="post">
                        <input type="submit" value=""/>
                        <table class="center">
                            <tr>
                            <td><label for="inputNom" class="control-label">Login</label></td>
                            <td><input type="pseudo" name="login" size="20" class="form-control" id="inputLogin" required placeholder="login"
                                   required value="<?= $data['login']??"" ?>"></td>
                            </tr>
                            <tr>
                            <td><label for="inputMDP" class="control-label">MDP</label></td>
                            <td><input type="password" name="password" size="20" class="form-control" required id="inputMDP"
                                   placeholder="Mot de passe"></td>
                            </tr>
                        </table>

                    <h1>S'inscrire</h1>
                   <span class="pull-right"> <button type="button" class="btn btn-lg btn-primary"><a href="<?= $pathFor['adduser'] ?>">S'enregistrer</a></button></span>


                    </form>

            </div>
        </div>
</div>

<?php

include("footer.php");