<?php
ob_start(); // Démarre le tampon de sortie
session_start([
    'cookie_lifetime' => 86400, // Durée de vie des cookies de session (1 jour)
    'cookie_secure' => true,    // Assure que les cookies ne sont envoyés que via HTTPS
    'cookie_httponly' => true,  // Empêche JavaScript d'accéder aux cookies de session
]);

include('database.php');

// Désactiver l'affichage des erreurs en production
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$debug_output = "";

function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit();
    } else {
        error_log("Headers already sent. Cannot redirect to $url.");
    }
}

ob_end_flush(); // Envoie la sortie tamponnée

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Username']) && isset($_POST['Password'])) {
        $username = $_POST['Username'];
        $password = $_POST['Password'];

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

            if (password_verify($password, $stored_password)) {
                $_SESSION['ID'] = $id;
                $_SESSION['Username'] = $username;
                $_SESSION['FirstName'] = $first_name;
                $_SESSION['LastName'] = $last_name;
                $_SESSION['Email'] = $email;
                $_SESSION['roles_id'] = $roles_id;

                $stmt->close();
                $conn->close();

                if ($roles_id == 1) {
                    redirect('index.php');
                } else {
                    redirect('indexuser.php');
                }
            } else {
                $stmt->close();
                $conn->close();
                error_log("Invalid username or password - password mismatch");
                echo "Invalid username or password.";
            }
        } else {
            $stmt->close();
            $conn->close();
            error_log("Invalid username or password - no such user");
            echo "Invalid username or password.";
        }
    } else {
        error_log("Username and password are required.");
        echo "Username and password are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
