<?php
session_start(); 
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the session
$first_name = $_SESSION['FirstName'];
$last_name = $_SESSION['LastName'];
$email = $_SESSION['Email'];
$roles_id = $_SESSION['roles_id'];

// Retrieve the roles_id from the session
$roles_id = isset($_SESSION['roles_id']) ? $_SESSION['roles_id'] : null; // Ensure it's set, or set to null if not available

include('database.php');

$userID = $_SESSION['ID'];

// Prepare and execute the query to get user information
$stmt = $conn->prepare("SELECT FirstName, LastName, Username, Email, roles_id FROM ks_user WHERE ID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($firstName, $lastName, $Username,  $email, $roles_id);
$stmt->fetch();
$stmt->close();
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
        .sidebar {
            background-color: #f8f9fa;
            border-right: 1px solid #e3e6f0;
        }
        .sidebar .nav-item .nav-link {
            color: #212529;
        }
        .sidebar .nav-item .nav-link.active {
            background-color: #007bff;
            color: #ffffff;
        }
        .sidebar .nav-item .nav-link:hover {
            background-color: #343a40;
            color: #ffffff;
        }
        .content-wrapper {
            padding: 20px;
        }
        .profile-card {
            background-color: #ffffff;
            border: 1px solid #e3e6f0;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        .profile-card h4 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }
        .profile-info label {
            font-weight: 600;
            color: #555;
        }
        .profile-info .form-control-plaintext {
            border: none;
            padding-left: 0;
        }
        .badge-opacity-success {
            background-color: #28a745;
            color: #ffffff;
        }
        .footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 10px 0;
        }
        .nav-item .nav-link {
            font-size: 1rem;
            padding: 10px 15px;
        }
        /* Updated table styling */
        table.profile-table {
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
        }
        table.profile-table th, table.profile-table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        table.profile-table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
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
                <?php if ($_SESSION['roles_id'] == 1 || $_SESSION['roles_id'] == 3): // Admins and Super Admins can see this ?>
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
        <?php endif; ?>
                <?php if ($roles_id == 3): // Display for Super Admin only ?>
        <li class="nav-item">
            <a class="nav-link" href="admin.php">
                <i class="fas fa-users menu-icon"></i>
                <span class="menu-title">Manage Users</span>
            </a>
        </li>
        <?php endif; ?>
            </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="col-12 grid-margin">
              <div class="card profile-card">
                <div class="card-body">
                  <h4 class="card-title">Profile Information</h4>
                  <table class="profile-table">
                    <thead>
                      <tr>
                        <th>Field</th>
                        <th>Details</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>First Name</td>
                        <td><?php echo htmlspecialchars($firstName); ?></td>
                      </tr>
                      <tr>
                        <td>Last Name</td>
                        <td><?php echo htmlspecialchars($lastName); ?></td>
                      </tr>
                      <tr>
                        <td>Username</td>
                        <td><?php echo htmlspecialchars($Username); ?></td>
                      </tr>
                      <tr>
                        <td>Email</td>
                        <td><?php echo htmlspecialchars($email); ?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
