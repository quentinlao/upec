<h1>Modifier une personne</h1>
<form action="" method="post">
    <table>
        <tr><td>Nom</td><td><input type="text" name="nom" value="<?php
                echo htmlspecialchars($nom);?>"/></td></tr>
        <tr><td>Prenom</td><td><input type="text" name="prenom" value="<?php
                echo htmlspecialchars($prenom);?>"/></td></tr>
    </table>

    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
</form>
