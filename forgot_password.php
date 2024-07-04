<!-- forgot_password.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - VotreApplication</title>
    <!-- Inclure les liens vers vos styles CSS -->
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- Styles spécifiques à votre application -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/kroschu1.png" />
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
                                                <h4>Forgot password?</h4>
                                                <p>"Please enter your email address to receive a password reset link</p>
                                                <form class="pt-3" method="post" action="send_reset_link.php">
                                                    <div class="form-group">
                                                        <input type="email" name="email" class="form-control form-control-lg" id="email" placeholder="Entrez votre adresse e-mail" required>
                                                    </div>
                                                    <div class="mt-6 d-grid gap-7">
                                                        <button class="btn btn-primary btn-rounded btn-fw" type="submit">To send the password reset link</button>
                                                    </div>
                                                </form>
                                                <p class="text-center mt-2 fw-light">
                                                    <a href="login.php" class="auth-link text-black">Back to login</a>
                                                </p>
                                            </div>
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- Inclure les liens vers vos scripts JS -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- Scripts spécifiques à votre application -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/todolist.js"></script>
</body>
</html>
