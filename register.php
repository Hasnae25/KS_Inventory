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

        // Insert user into database
        $query = "INSERT INTO ks_user (Username, FirstName, LastName, Email, Password) 
                  VALUES ('$username', '$firstName', '$lastName', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            // User inserted successfully, redirect to login page
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
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
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-200">
            <div class="col-lg-9 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="card">
                  <div class="card-body">
                    <blockquote class="blockquote">
                <div class="brand-logo">
                  <img src="assets/images/logo.png" alt="logo">
                </div>
                <h4>New here?</h4>
                <h6 class="fw-light">Signing up is easy. It only takes a few steps</h6>
                <form class="pt-3" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="form-group">
        <input type="text" class="form-control form-control-lg" id="Username" name="Username" placeholder="Username">
    </div>
    <div class="form-group">
        <input type="text" class="form-control form-control-lg" id="FirstName" name="FirstName" placeholder="FirstName">
    </div>
    <div class="form-group">
        <input type="text" class="form-control form-control-lg" id="LastName" name="LastName" placeholder="LastName">
    </div>
    <div class="form-group">
        <input type="email" class="form-control form-control-lg" id="Email" name="Email" placeholder="Email">
    </div>
    <div class="form-group">
        <input type="password" class="form-control form-control-lg" id="Password" name="Password" placeholder="Password">
    </div>
    <div class="form-group">
        <input type="password" class="form-control form-control-lg" id="exampleInputConfirmPassword1" name="confirmPassword" placeholder="Confirm Password">
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
    <div class="text-center mt-4 fw-light">Already have an account? <a href="login.php" class="text-primary">Login</a>
    </blockquote>
  </div>
</form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
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