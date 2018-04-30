<h1>Modifier l'identification pour la personne</h1>
<form action="" method="post">
    <table>
        <tr><td>Pid</td><td><input type="hidden" name="pid" value="<?php
                echo htmlspecialchars($id);?>"/><?php
                echo htmlspecialchars($id);?></td></tr>
        <tr><td>Tid</td><td><input type="hidden" name="tid" value="<?php
                echo htmlspecialchars($tid);?>"/><?php
                echo htmlspecialchars($tid);?></td></tr>
        <tr><td>Valeur</td><td><input type="text" name="valeur" value="<?php
                echo htmlspecialchars($valeur);?>"/></td></tr>
    </table>
    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
</form>