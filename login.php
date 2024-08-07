<?php
// login.php
if (headers_sent($file, $line)) {
  die("Headers already sent in $file on line $line");
}
session_start();

include('database.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$debug_output = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    // Check if username and password are set
    if (isset($_POST['Username']) && isset($_POST['Password'])) {
        $username = $_POST['Username'];
        $password = $_POST['Password'];

        // Debugging output
        $debug_output .= "Username and password are set. Username: $username\n";

        // Prepare and bind
        $stmt = $conn->prepare("SELECT ID, Password, FirstName, LastName, Email, roles_id FROM ks_user WHERE Username = ?");
        if ($stmt === false) {
            $debug_output .= "Error preparing the statement: " . $conn->error . "\n";
            die("Error preparing the statement: " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Debugging output
        $debug_output .= "Executed the statement. Number of rows: " . $stmt->num_rows . "\n";

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $stored_password, $first_name, $last_name, $email, $roles_id);
            $stmt->fetch();

            // Debugging output
            $debug_output .= "Fetched Password: " . htmlspecialchars($stored_password) . "\n";

            // Plaintext password comparison
            if ($password === $stored_password) {
                $_SESSION['ID'] = $id;
                $_SESSION['Username'] = $username;
                $_SESSION['FirstName'] = $first_name;
                $_SESSION['LastName'] = $last_name;
                $_SESSION['Email'] = $email;
                $_SESSION['roles_id'] = $roles_id;

                $stmt->close();
                $conn->close();
                if ($roles_id == 1) {
                  // Redirect to admin dashboard
                  header('Location: index.php');
                  exit();
              } else {
                  // Redirect to user dashboard
                  header('Location: indexuser.php');
                  exit();
              }
            } else {
                $stmt->close();
                $conn->close();
                $debug_output .= "Invalid username or password - password mismatch\n";
                echo "Invalid username or password.";
            }
        } else {
            $stmt->close();
            $conn->close();
            $debug_output .= "Invalid username or password - no such user\n";
            echo "Invalid username or password.";
        }
    } else {
        $debug_output .= "Username and password are required.\n";
        echo "Username and password are required.";
    }
} 


echo nl2br($debug_output);

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
                    <a href="forgot_password.php">Forgot password?</a>
                    <a href="register.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
