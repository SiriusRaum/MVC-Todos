<?php

try {
    // A. Instancier (construire) un objet PDO
    $pdo = new PDO('mysql:host=localhost;dbname=todos;charset=utf8mb4', 'root', 'root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


    if ($registerUser) {

        // A.BIS : Vérifier si l'email est disponible
        $request = $pdo->prepare('SELECT u.firstname, u.lastname, u.email FROM users u WHERE u.email = ?');
        $request->bindParam(1, $email, PDO::PARAM_STR);
        $request->execute();

        // fetch() uniquement : je n'ai besoin que d'une seule ligne au maximum
        $data = $request->fetch(PDO::FETCH_ASSOC);

        if (empty($data)) {
            // B. Préparer la requête d'insertion
            $request = $pdo->prepare('INSERT INTO users (firstname, lastname, email, `password`) VALUES (?, ?, ?, ?)');

            // C. BindParam pour remplir les ? de la requête
            $request->bindParam(1, $prenom, PDO::PARAM_STR);
            $request->bindParam(2, $nom, PDO::PARAM_STR);
            $request->bindParam(3, $email, PDO::PARAM_STR);
            $request->bindParam(4, $password, PDO::PARAM_STR);

            // D. Envoyer la requête à la BDD
            $request->execute();

            // E. Message de confirmation
            $message = "L'utilisateur a été enregistré : " . htmlspecialchars("{$prenom} {$nom} - {$email}");

        } else {
            $message = "Cet email est déjà utilisé par un autre compte.";
        }
    }

    // RÉCUPÉRATION DES UTILISATEURS À AFFICHER
    $request = $pdo->prepare('SELECT u.firstname, u.lastname, u.email FROM users u');
    $request->execute();
    $users = $request->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $error) {
    // On n'affiche jamais le message technique à l'utilisateur
    error_log($error->getMessage());
    $message = "Une erreur est survenue, veuillez réessayer plus tard.";
}