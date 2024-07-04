<?php
// Démarrez la session
session_start();
   
// Détruisez toutes les variables de session
session_unset();
   
// Détruisez la session
session_destroy();
   
// Redirigez l'utilisateur vers une autre page après la déconnexion
header("Location: index.php");
exit();
?>
