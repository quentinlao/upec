<?php
$title = "Inscription";
$path = "";
include("auth/EtreInvite.php");
include("header.php");
if (empty(($_POST['login']))) {
    include('adduser_form.php');
    exit();
}

$error = "";
//foreach (['nom', 'prenom', 'login', 'mdp', 'mdp2'] as $name) {
foreach (['login', 'mdp', 'mdp2'] as $name) {
    if (empty(htmlspecialchars($_POST[$name]))) {
        $error .= "La valeur du champs '$name' ne doit pas être vide";
    } else {
        $data[$name] = htmlspecialchars($_POST[$name]);
    }
}


// Vérification si l'utilisateur existe
$SQL = "SELECT uid FROM users WHERE login=?";
$stmt = $db->prepare($SQL);
$res = $stmt->execute([$data['login']]);

if ($res && $stmt->fetch()) {
    $error .= " ";
    error("Login déjà utilisé");
}

if (htmlspecialchars($data['mdp']) != htmlspecialchars(($data['mdp2']))) {

    $error .= " ";
    error("MDP ne correspondent pas");
}

if (!empty($error)) {
    include('adduser_form.php');
    exit();
}

//foreach (['nom', 'prenom', 'login', 'mdp'] as $name) {
foreach (['login', 'mdp'] as $name) {
    $clearData[$name] = $data[$name];
}

$passwordFunction =
    function ($s) {
        return password_hash($s, PASSWORD_DEFAULT);
    };

$clearData['mdp'] = $passwordFunction($data['mdp']);

try {
    // $SQL = "INSERT INTO users(nom,prenom,login,mdp) VALUES (:nom,:prenom,:login,:mdp)";
    $SQL = "INSERT INTO users(login,mdp) VALUES (:login,:mdp)";
    $stmt = $db->prepare($SQL);
    $res = $stmt->execute($clearData);
    $id = $db->lastInsertId();
    $auth->authenticate($clearData['login'], $data['mdp']);
    // echo "Utilisateur $clearData[nom] : " . $id . " ajouté avec succès.";
    redirect($pathFor['root']);
} catch (\PDOException $e) {
    http_response_code(500);
    error("Erreur de serveur.");
    exit();
}




