<?php
session_start();
ob_start(); // Démarre la mise en tampon de sortie
include('database.php');

// Affichage des erreurs pour le débogage (désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit();
    } else {
        error_log("Headers already sent. Cannot redirect to $url.");
        echo "<script>window.location.href='$url';</script>"; // Redirection via JavaScript en cas d'échec
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Username']) && isset($_POST['Password'])) {
        $username = $_POST['Username'];
        $password = $_POST['Password'];

        // Requête SQL pour récupérer l'utilisateur avec le mot de passe haché
        $stmt = $conn->prepare("SELECT ID, Password, FirstName, LastName, Email, roles_id FROM ks_user WHERE Username = ?");
        if ($stmt === false) {
            error_log("Error preparing the statement: " . $conn->error);
            die("Error preparing the statement.");
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $stored_password, $first_name, $last_name, $email, $roles_id);
            $stmt->fetch();

            // Utiliser password_verify pour comparer le mot de passe en clair avec le haché
            if (password_verify($password, $stored_password)) {
                // Si le mot de passe est correct, on initialise la session
                $_SESSION['ID'] = $id;
                $_SESSION['Username'] = $username;
                $_SESSION['FirstName'] = $first_name;
                $_SESSION['LastName'] = $last_name;
                $_SESSION['Email'] = $email;
                $_SESSION['roles_id'] = $roles_id;

                // Redirection selon le rôle de l'utilisateur
                if ($roles_id == 1) {
                    redirect('index.php');  // Page admin
                } elseif ($roles_id == 2) {
                    redirect('indexuser.php');  // Page utilisateur
                } elseif ($roles_id == 3) {
                    redirect('superadmin.php');  // Page super admin
                }
            } else {
                echo "Le mot de passe est incorrect.<br>";
                error_log("Invalid username or password - password mismatch");
            }
        } else {
            echo "Utilisateur introuvable.<br>";
            error_log("Invalid username or password - no such user");
        }

        $stmt->close();
        $conn->close();
    } else {
        error_log("Username and password are required.");
        echo "Username and password are required.";
    }
}

ob_end_flush(); // Envoie tout le contenu mis en tampon et arrête la mise en tampon
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KsInventory</title>
     <!-- Bootstrap 5 CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/kroschu1.png" />

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #d7bbf5, #af92ea);
        }
        .container {
            display: flex;
            width: 800px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .welcome {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.5);
        }
        .welcome img {
            max-width: 100px; /* Adjust the size here */
            max-height: 100px; /* Adjust the size here */
            margin-bottom: 20px;
          }
            .login img {
            max-width: 150px; /* Adjust the size here */
            max-height: 150px; /* Adjust the size here */
            margin-bottom: 20px;
        }
        .welcome h2 {
            margin: 0;
            font-size: 24px;
            color: #6a1b9a;
        }
        .welcome p {
            margin: 10px 0 0;
            color: #6a1b9a;
        }
        .login {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #fff;
        }
        .login h2 {
            margin: 0 0 20px;
            font-size: 24px;
            color: #333;
        }
        .login form {
            width: 100%;
        }
        .form-group {
            margin-bottom: 20px;
            width: 100%;
        }
        .form-group input {
            width: calc(100% - 30px);
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 5px;
            background: #6a1b9a;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background: #4a148c;
        }
        .links {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .links a {
            color: #6a1b9a;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome">
 
            <h2>Welcome to Kromberg&Schubert</h2>
        </div>
        <div class="login">
        <img src="assets/images/logo.png" alt="Company Logo">
            <form method="post" action="login.php">
                <div class="form-group">
                    <label for="Username">Username</label>
                    <input type="text" name="Username" id="Username" placeholder="Type your Username" required>
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <input type="password" name="Password" id="Password" placeholder="Type your Password" required>
                </div>
                <button type="submit" class="btn">Sign In</button>
                <div class="links">
                    <a href="register.php">Sign Up</a>
                    
                </div>
            </form>
        </div>
    </div>
</body>
</html>
