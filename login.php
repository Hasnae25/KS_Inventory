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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KSInventory</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="assets/images/kroschu1.png" />
    <!-- Custom CSS for modern look -->
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Roboto', sans-serif;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 300px;
            backdrop-filter: blur(10px);
            text-align: center;
            color: white;
        }
        .login-container img {
            width: 80px;
            margin-bottom: 20px;
        }
        .login-container h4 {
            margin-bottom: 10px;
            font-size: 24px;
        }
        .login-container .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .login-container .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 5px;
            color: white;
        }
        .login-container .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .login-container .btn {
            background: #667eea;
            border: none;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .login-container .btn:hover {
            background: #5568c8;
        }
        .login-container .auth-link {
            color: rgba(255, 255, 255, 0.7);
        }
        .login-container .auth-link:hover {
            color: white;
        }
        .login-container .form-check-label {
            color: rgba(255, 255, 255, 0.7);
        }
        .login-container .text-primary {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-logo">
            <img src="assets/images/logo.png" alt="logo">
        </div>
        <h4>Login</h4>
        <h6 class="fw-light">Sign in to continue.</h6>
        <!-- Display error message if any -->
        <?php if (!empty($error)): ?>
            <div><?php echo $error; ?></div>
        <?php endif; ?>
        <form class="pt-3" method="post" action="login.php">
            <div class="form-group">
                <input type="text" name="Username" class="form-control form-control-lg" id="Username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="Password" class="form-control form-control-lg" id="Password" placeholder="Password" required>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary btn-lg btn-block" type="submit">SIGN IN</button>
            </div>
            <div class="my-2 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                </div>
                <a href="forgot_password.php" class="auth-link text-black">Forgot password?</a>
            </div>
            <div class="text-center mt-4 fw-light"> Don't have an account? <a href="register.php" class="text-primary">Create</a>
            </div>
        </form>
    </div>
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- endinject -->
</body>
</html>
