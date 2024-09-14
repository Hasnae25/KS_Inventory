<?php
session_start();
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

include('database.php');
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the session message after retrieving it
} else {
    $message = ""; 
}

// Check if the user is an admin
if ($_SESSION['roles_id'] != 3) {
    header('Location: indexuser.php');
    exit();
}

// Fetch all users
$query = "SELECT ks_user.ID, ks_user.Username, ks_user.FirstName, ks_user.LastName, ks_user.Email, ks_roles.role_name
          FROM ks_user
          JOIN ks_roles ON ks_user.roles_id = ks_roles.id";
$result = mysqli_query($conn, $query);

// Update user role
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $newRoleId = $_POST['role_id'];
    
    $updateQuery = "UPDATE ks_user SET roles_id = ? WHERE ID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $newRoleId, $userId);
    if ($stmt->execute()) {
        header('Location: admin.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KsInventory</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/kroschu1.png" />

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar .navbar-brand {
            color: #ffffff;
        }
        .navbar .navbar-brand img {
            height: 40px;
        }
        .navbar .nav-link {
            color: #ffffff;
        }
        .sidebar .nav-item .nav-link {
            color: #212529;
        }
        .sidebar .nav-item .nav-link.active {
            background-color: #007bff;
            color: #ffffff;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .alert-message {
            margin-top: 20px;
        }
        .content-wrapper {
            padding: 20px;
        }
        .footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 10px 0;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .badge-opacity-warning {
            background-color: #ffc107;
            color: #212529;
        }
    </style>
</head>
<body>
<div class="container-scroller">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/logo.png" alt="logo" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            <i class="mdi mdi-account-outline text-primary me-2"></i> My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="mdi mdi-power text-primary me-2"></i> Sign Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid page-body-wrapper mt-5 pt-3">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        <i class="fas fa-th-large menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Storage.php">
                        <i class="fas fa-database menu-icon"></i>
                        <span class="menu-title">Storage</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Scrap.php">
                        <i class="fas fa-trash menu-icon"></i>
                        <span class="menu-title">Scrap</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="alert.php">
                        <i class="fas fa-exclamation-triangle menu-icon"></i>
                        <span class="menu-title">Low Stock Alert</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">
                        <i class="fas fa-users menu-icon"></i>
                        <span class="menu-title">Manage Users</span>
                    </a>
                </li>
            </ul>
        </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="col-16 grid-margin">
                        <div class="card-body">
                            <h4 class="card-title">Manage Users</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><h1  class="badge badge-opacity-warning me-40"> UserName</h1></th>
                                            <th><h1  class="badge badge-opacity-warning me-40"> FirstName </h1></th>
                                            <th><h1  class="badge badge-opacity-warning me-40"> LastName </h1></th>
                                            <th><h1  class="badge badge-opacity-warning me-40">Email</h1></th>
                                            <th><h1  class="badge badge-opacity-warning me-40"> Role</h1></th>
                                            <th><h1  class="badge badge-opacity-warning me-40"> Change Role</h1></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['Username']); ?></td>
                                                <td><?php echo htmlspecialchars($row['FirstName']); ?></td>
                                                <td><?php echo htmlspecialchars($row['LastName']); ?></td>
                                                <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                                <td><?php echo htmlspecialchars($row['role_name']); ?></td>
                                                <td>
                                                    <form method="POST" action="admin.php">
                                                        <input type="hidden" name="user_id" value="<?php echo $row['ID']; ?>">
                                                        <select name="role_id" class="form-select">
                                                            <option value="1" <?php if ($row['role_name'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                                            <option value="2" <?php if ($row['role_name'] == 'User') echo 'selected'; ?>>User</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-primary mt-2">Change</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:../../partials/_footer.html -->
                    <footer class="footer">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block"><a href="https://www.bootstrapdash.com/" target="_blank"></a></span>
                            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Hasnae Tazi</span>
                        </div>
                    </footer>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
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
        <!-- jQuery and jQuery UI -->
        <script src="assets/jquery/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="assets/jquery/jquery-ui.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    </body>
</html>

<?php
mysqli_close($conn);
?>
