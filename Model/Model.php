<?php
class Model {
    //attributs
    private PDO $pdo;

    //constructeur
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    //methode
    // Récupération de l'objet PDO
    public function getPdo(): PDO {
        return $this->pdo;
        }
        // Vérification de l'existence d'un email
        public function emailExists($email): bool
{
    $request = $this->pdo->prepare('SELECT 1 FROM users WHERE email = ?');
    $request->execute([$email]);
 
    return $request->fetch() !== false;
}
// Insertion d'un nouvel utilisateur
public function insertUser(string $prenom, string $nom, string $email, string $passwordHash): void
{
    $request = $this->pdo->prepare(
        'INSERT INTO users (firstname, lastname, email, `password`) VALUES (?, ?, ?, ?)'
    );
    $request->execute([$prenom, $nom, $email, $passwordHash]);
}
// Récupération de tous les utilisateurs
public function getAllUsers(): array
{
$request = $this->pdo->prepare('SELECT firstname, lastname, email FROM users');
    $request->execute();
 
    return $request->fetchAll(PDO::FETCH_ASSOC);
}
}