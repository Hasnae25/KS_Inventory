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
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Set default role ID for a user 
        $defaultRoleId = 2;

        // Insert user into database
        $query = "INSERT INTO ks_user (Username, FirstName, LastName, Email, Password, roles_id) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $username, $firstName, $lastName, $email, $hashedPassword, $defaultRoleId);
        
        if ($stmt->execute()) {
            // User inserted successfully, redirect to login page
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            max-width: 100px;
            max-height: 100px;
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
        .error {
            color: red;
            font-size: 0.9em;
            text-align: left;
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
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="signupForm">
                <div class="form-group">
                    <label for="Username">Username</label>
                    <input type="text" name="Username" id="Username" placeholder="Type your Username" required>
                </div>
                <div class="form-group">
                    <label for="FirstName">First Name</label>
                    <input type="text" name="FirstName" id="FirstName" placeholder="Type your First Name" required>
                </div>
                <div class="form-group">
                    <label for="LastName">Last Name</label>
                    <input type="text" name="LastName" id="LastName" placeholder="Type your Last Name" required>
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" name="Email" id="Email" placeholder="Type your Email" required>
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <input type="password" name="Password" id="Password" placeholder="Type your Password" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputConfirmPassword1">Confirm Password</label>
                    <input type="password" name="confirmPassword" id="exampleInputConfirmPassword1" placeholder="Confirm your Password" required>
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
                    <button class="btn" type="submit">SIGN UP</button>
                </div>
                <div class="text-center mt-4 fw-light">Already have an account? <a href="login.php" class="text-primary">Login</a></div>
            </form>
        </div>
    </div>
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
</body>
</html>
