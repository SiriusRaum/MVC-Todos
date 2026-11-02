<?php

require_once '../Model/Model.php';
require_once '../View/View.php';



// Algorithme d'inscription : retourne le message à afficher
function registerUser(PDO $pdo): string
{
    // 1) Tous les champs sont-ils remplis ?
    if (empty($_POST['prenom']) or empty($_POST['nom']) or empty($_POST['email']) or empty($_POST['password'])) {
        return "Veuillez remplir tous les champs du formulaire.";
    }

    // 2) Nettoyage
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 3) Vérification du format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Votre email n'est pas au bon format.";
    }
// 4) Vérification de la longueur du mot de passe
    if (mb_strlen($password, 'UTF-8') < 12) {
        return "Votre mot de passe doit faire au moins 12 caractères.";
    }

    // 5) L'email est-il disponible ?
    if (emailExists($pdo, $email)) {
        return "Cet email est déjà utilisé par un autre compte.";
    }

    // 6) Hash du mot de passe puis enregistrement
    insertUser($pdo, $prenom, $nom, $email, password_hash($password, PASSWORD_DEFAULT));

    return "L'utilisateur a été enregistré : "
        . htmlspecialchars("{$prenom} {$nom} - {$email}");
}

// Fonction principale de la page
function Controller(): void
{
    $message = '';
    $users = [];

    try {
        $pdo = getPdo();

        if (isset($_POST['submit'])) {
            $message = registerUser($pdo);
        }

        $users = getAllUsers($pdo);
         } catch (PDOException $error) {
        $message = $error->getMessage();   
    }

    renderView($message, $users);
}
