<?php

class View{
//attributs 


//constructeur


//method
public function render($message, $users): void {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos - Inscription</title>
</head>
<body>
    <main>
        <h1>Page d'Accueil</h1>

        <section>
            <h2>Inscription</h2>
            <form action="" method="post">
                <label for="prenom">Prénom <input type="text" name="prenom" id="prenom"></label>
                <label for="nom">Nom <input type="text" name="nom" id="nom"></label>
                <label for="email">Email <input type="email" name="email" id="email"></label>
                <label for="password">Mot de passe <input type="password" name="password" id="password"></label>
                <input type="submit" name="submit" value="S'inscrire">
            </form>
            <p><?= $message ?></p>
        </section>

        <section>
            <h2>Liste des utilisateurs</h2>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li>
                        <?= htmlspecialchars($user['firstname']) ?>
                        <?= htmlspecialchars($user['lastname']) ?>
                        - <?= htmlspecialchars($user['email']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>
</html>
            <?php    }
}