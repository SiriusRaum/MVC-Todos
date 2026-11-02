<?php

require_once '../Model/Model.php';
require_once '../View/View.php';

class Controller
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    // Action de la page d'accueil
    public function home(): void
    {
        $message = '';
        $users = [];

        try {
            $model = new Model($this->connect());

            if (isset($_POST['submit'])) {
                $message = $this->register($model);
            }

            $users = $model->getAllUsers();
        } catch (PDOException $error) {
            // On n'affiche jamais le message technique à l'utilisateur
            error_log($error->getMessage());
            $message = "Une erreur est survenue, veuillez réessayer plus tard.";
        }

        $this->view->render($message, $users);
    }

    // Création de la connexion à la BDD
    private function connect(): PDO
    {
        return new PDO(
            'mysql:host=localhost;dbname=todos;charset=utf8mb4',
            'root',
            'root',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    // Algorithme d'inscription : retourne le message à afficher
    private function register(Model $model): string
    {
        // 1) Tous les champs sont-ils remplis ?
        if (
            empty($_POST['prenom']) || empty($_POST['nom'])
            || empty($_POST['email']) || empty($_POST['password'])
        ) {
            return "Veuillez remplir tous les champs du formulaire.";
        }

        // 2) Nettoyage (sauf le mot de passe)
        $prenom = trim($_POST['prenom']);
        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // 3) Vérification du format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Votre email n'est pas au bon format.";
        }

        if (mb_strlen($password, 'UTF-8') < 12) {
            return "Votre mot de passe doit faire au moins 12 caractères.";
        }

        // 4) L'email est-il disponible ?
        if ($model->emailExists($email)) {
            return "Cet email est déjà utilisé par un autre compte.";
        }

        // 5) Hash du mot de passe puis enregistrement
        $model->insertUser($prenom, $nom, $email, password_hash($password, PASSWORD_DEFAULT));

        return "L'utilisateur a été enregistré : "
            . htmlspecialchars("{$prenom} {$nom} - {$email}");
    }
}