<?php
// Variables d'affichage (utilisées par la View)
$message = '';
$users = [];

// Indique au Model s'il doit enregistrer un utilisateur
$registerUser = false;

// ALGORITHME D'INSCRIPTION D'UN UTILISATEUR
// 1) Vérifier que je reçois un formulaire d'inscription
if (isset($_POST['submit'])) {

    // 2) Vérifier que tous les champs sont remplis
    if (empty($_POST['prenom']) or empty($_POST['nom']) or empty($_POST['email']) or empty($_POST['password'])) {
        $message = "Veuillez remplir tous les champs du formulaire.";

    // 3.a) Vérifier le format de l'email
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $message = "Votre email n'est pas au bon format.";

    // 3.b) Vérifier la longueur du mot de passe
    } elseif (mb_strlen($_POST['password'], 'UTF-8') < 12) {
        $message = "Votre mot de passe doit faire au moins 12 caractères.";

    } else {
        // 4) Nettoyage des données 
        $prenom = trim($_POST['prenom']);
        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);

        // 5) Hasher le mot de passe
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Les données sont valides : le Model peut les enregistrer
        $registerUser = true;
    }
}

// Appel du Model : enregistrement (si $registerUser) + récupération de la liste ($users)
require '../Model/Model.php';

// Appel de la View : affichage de $message et $users
require '../View/View.php';