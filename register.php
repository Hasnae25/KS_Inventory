<?php
// Start session
session_start();

// Include database connection
include('database.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to store validation errors
    $errors = array();

    // Retrieve form data and sanitize inputs
    $username = isset($_POST['Username']) ? mysqli_real_escape_string($conn, $_POST['Username']) : '';
    $firstName = isset($_POST['FirstName']) ? mysqli_real_escape_string($conn, $_POST['FirstName']) : '';
    $lastName = isset($_POST['LastName']) ? mysqli_real_escape_string($conn, $_POST['LastName']) : '';
    $email = isset($_POST['Email']) ? mysqli_real_escape_string($conn, $_POST['Email']) : '';
    $password = isset($_POST['Password']) ? mysqli_real_escape_string($conn, $_POST['Password']) : '';

    // Validate form fields
    if (empty($username)) {
        $errors['Username'] = "Username is required";
    }

    if (empty($firstName)) {
        $errors['FirstName'] = "First Name is required";
    }

    if (empty($lastName)) {
        $errors['LastName'] = "Last Name is required";
    }

    if (empty($email)) {
        $errors['Email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['Email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['Password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['Password'] = "Password must be at least 6 characters long";
    }

    // If there are no validation errors, proceed with inserting user into database
    if (empty($errors)) {
        /* Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        echo strlen($assword); */

        // Set default role ID for a user 
        $defaultRoleId = 2;

        // Insert user into database
        $query = "INSERT INTO ks_user (Username, FirstName, LastName, Email, Password, roles_id) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $username, $firstName, $lastName, $email, $password, $defaultRoleId);
        
        if ($stmt->execute()) {
            // User inserted successfully, redirect to login page
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
            echo "<br>Values: " . $username . ", " . $firstName . ", " . $lastName . ", " . $email . ", " . $password . ", " . $defaultRoleId;
        }

        $stmt->close();
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KsInventory</title>
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
        .signup-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 350px;
            backdrop-filter: blur(10px);
            text-align: center;
            color: white;
        }
        .signup-container img {
            width: 80px;
            margin-bottom: 20px;
        }
        .signup-container h4 {
            margin-bottom: 10px;
            font-size: 24px;
        }
        .signup-container .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .signup-container .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 5px;
            color: white;
        }
        .signup-container .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .signup-container .btn {
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
        .signup-container .btn:hover {
            background: #5568c8;
        }
        .signup-container .auth-link {
            color: rgba(255, 255, 255, 0.7);
        }
        .signup-container .auth-link:hover {
            color: white;
        }
        .signup-container .form-check-label {
            color: rgba(255, 255, 255, 0.7);
        }
        .signup-container .text-primary {
            color: #667eea;
        }
        .error {
            color: red;
            font-size: 0.9em;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="brand-logo">
            <img src="assets/images/logo.png" alt="logo">
        </div>
        <h4>New here?</h4>
        <h6 class="fw-light">Signing up is easy. It only takes a few steps</h6>
        <form class="pt-3" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="signupForm">
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="Username" name="Username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="FirstName" name="FirstName" placeholder="First Name" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="LastName" name="LastName" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control form-control-lg" id="Email" name="Email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control form-control-lg" id="Password" name="Password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control form-control-lg" id="exampleInputConfirmPassword1" name="confirmPassword" placeholder="Confirm Password" required>
            </div>
            <div class="mb-4">
                <div class="form-check">
                    <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> I agree to all Terms & Conditions 
                    </label>
                </div>
            </div>
            <!-- Display validation errors, if any -->
            <?php if (!empty($errors)) { ?>
                <div class="error">
                    <?php foreach ($errors as $error) {
                        echo $error . "<br>";
                    } ?>
                </div>
            <?php } ?>

            <div class="mt-3 d-grid gap-2">
                <button class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn" type="submit">SIGN UP</button>
            </div>
            <div class="text-center mt-4 fw-light">Already have an account? <a href="login.php" class="text-primary">Login</a></div>
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
    <!-- Password matching validation -->
    <script>
        document.getElementById("signupForm").addEventListener("submit", function(event) {
            var password = document.getElementById("Password").value;
            var confirmPassword = document.getElementById("exampleInputConfirmPassword1").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                event.preventDefault();
            }
        });
    </script>
    <!-- endinject -->
</body>
</html>
