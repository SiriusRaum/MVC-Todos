<?php
// création du pdo pour la connexion à la BDD
function getPdo(): PDO
{
    return new PDO('mysql:host=localhost;dbname=todos;charset=utf8mb4','root','root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
//Vérification si l'email existe déjà dans la BDD
function emailExists(PDO $pdo, string $email): bool
{
    $request = $pdo->prepare('SELECT 1 FROM users WHERE email = ?');
    $request->execute([$email]);
 
    return $request->fetch() !== false;
}
// Insertion d'un nouvel utilisateur dans la BDD
function insertUser(PDO $pdo, string $prenom, string $nom, string $email, string $passwordHash): void
{
    $request = $pdo->prepare(
        'INSERT INTO users (firstname, lastname, email, `password`) VALUES (?, ?, ?, ?)'
    );
    $request->execute([$prenom, $nom, $email, $passwordHash]);
}
// Récupération de tous les utilisateurs
function getAllUsers(PDO $pdo): array
{
$request = $pdo->prepare('SELECT firstname, lastname, email FROM users');
    $request->execute();
 
    return $request->fetchAll(PDO::FETCH_ASSOC);
}