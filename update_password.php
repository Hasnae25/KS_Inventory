<?php
// update_password.php
include('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $token = $_POST['token'];

    // Valider le mot de passe (optionnel : vous pouvez appliquer des règles de validation ici)

    // Mettre à jour le mot de passe dans la base de données pour l'utilisateur associé au jeton
    // Exemple : UPDATE utilisateurs SET mot_de_passe = ? WHERE email = (SELECT email FROM password_reset_tokens WHERE token = ?)

    echo "Mot de passe mis à jour avec succès.";

    // Nettoyage : supprimer le jeton de la base de données après une réinitialisation de mot de passe réussie
    // Exemple : DELETE FROM password_reset_tokens WHERE token = ?
}
?>
