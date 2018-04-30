<h1>Modifier le mot de passe</h1>
<form action="" method="post">
    <table>
        <tr><td>Login : </td><td><b><?php
                    echo htmlspecialchars($login);?></b></td></tr>
        <tr><td>New mdp</td><td><input type="password"
                                    name="mdp" value="" /></td></tr>
        <tr><td>Again mdp</td><td><input type="password"
                                            name="mdp2" value="" /></td></tr>
    </table>

    <input class="btn btn-lg btn-success" type="submit" name="formSubmit" value="Envoyer" />
</form>