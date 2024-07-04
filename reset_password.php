<!-- reset_password.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Meta tags, titre et liens CSS -->
</head>
<body>
<div class="container">
    <h2>Réinitialisation du mot de passe</h2>
    <?php
    // Vérifier la validité du jeton
    $token = $_GET['token'];

    // Interroger la base de données pour vérifier si le jeton est valide et obtenir l'e-mail associé
    // Exemple : SELECT email FROM password_reset_tokens WHERE token = ?
    // Procédez avec le formulaire de réinitialisation s'il est valide, sinon affichez une erreur
    ?>
    <form method="post" action="update_password.php">
        <div class="form-group">
            <label for="password">Nouveau mot de passe :</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
    </form>
</div>
</body>
</html>
