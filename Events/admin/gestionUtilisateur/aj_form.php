
<h1>Ajouter un utilisateur</h1>
<form action="ajout.php" method="post">
    <table>
        <tr><td>Login</td><td><input type="text" name="login" /></td></tr>
        <tr><td>Mot de passe</td><td><input type="password" name="mdp" /></td></tr>
        <tr><td>Again mdp</td><td><input type="password" name="mdp2" /></td></tr>
        <tr><td>Role</td><td><input type="radio" name="role" value="user" />User <input type="radio" name="role" value="admin" /> Admin</td></tr>
    </table>
    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
</form>
<h2>Rappel</h2>
<p>
    Le role permet l'accès à la page administrations et utilisateurs.<br>
</p>