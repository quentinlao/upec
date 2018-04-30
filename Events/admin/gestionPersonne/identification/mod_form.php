<h1>Modifier l'identification pour la personne</h1>
<form action="" method="post">
    <table>
        <tr><td>Pid</td><td><?php
                echo htmlspecialchars($id);?></td></tr>
        <tr><td>Tid</td><td><?php
                echo htmlspecialchars($tid);?></td></tr>
        <tr><td>Valeur</td><td><input type="text" name="valeur" value="<?php
                echo htmlspecialchars($valeur);?>"/></td></tr>
    </table>
    <input type="submit" value="Envoyer" />
</form>