<?php
// login.php
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
        $stmt = $conn->prepare("SELECT ID, Password, FirstName, LastName, Email FROM ks_user WHERE Username = ?");
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
            $stmt->bind_result($id, $stored_password, $first_name, $last_name, $email);
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

                // Debugging output
                $debug_output .= "Password verified, redirecting to index.php\n";

                $stmt->close();
                $conn->close();
                header("Location: index.php");
                exit();
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
} else {
    $conn->close();
}


echo nl2br($debug_output);

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
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="assets/images/kroschu1.png" />
    <!--    PHP -->
    <!-- -->

  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-9 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="col-md-50 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <blockquote class="blockquote">
                    <div class="brand-logo">
                    <img src="assets/images/logo.png" alt="logo">
                <h4>Login</h4>
                <h6 class="fw-light">Sign in to continue.</h6>
                <!-- Display error message if any -->
                <?php if (!empty($error)): ?>
                  <div><?php echo $error; ?></div>
                <?php endif; ?>
                <form class="pt-3" method="post" action="login.php" >
                  <div class="form-group">
                    <input type="text" name="Username" class="form-control form-control-lg" id="Username" placeholder=" Type your Username" Required>
                  </div>
                  <div class="form-group">
                    <input type="password"  name="Password" class="form-control form-control-lg" id="Password" placeholder=" Type your Password" Required>
                  </div>
                  <div class="mt-6 d-grid gap-7">
                      <button class="btn btn-primary btn-rounded btn-fw" type="submit">SIGN IN</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                    </div>
                    <a href="forgot_password.php" class="auth-link text-black">Forgot password?</a>
                  </div>
                  <div class="text-center mt-2 fw-light"> Don't have an account? <a href="register.php" class="text-primary">Create</a>
                    </blockquote>
                  </div>
                </div>
              </div>
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
    <!-- endinject -->
  </body>
</html>
