<?php
ob_start();
session_start();
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the roles_id from the session
$roles_id = isset($_SESSION['roles_id']) ? $_SESSION['roles_id'] : null;

include('database.php');

// Affichage des erreurs pour le débogage (désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // Clear the session message after retrieving it

// Fetch scrap equipment data, including the deletion date
$sql = "SELECT `id_scr`, `id_eq`, `reason`, `sc_qte`, `eq_code`, `date_deleted` FROM `ks_scrap`";

$result = $conn->query($sql);
$scrapData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $scrapData[] = $row;
    }
} else {
    $scrapData = [];
}

$conn->close();

ob_end_flush();  // Vide le tampon de sortie et l'envoie au navigateur
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
                    <a class="nav-link" href="storage.php">
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
                <?php if ($roles_id == 3): ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">
                        <i class="fas fa-users menu-icon"></i>
                        <span class="menu-title">Manage Users</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Main Panel -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="col-16 grid-margin">
                    <div class="card-body">
                        <h4 class="card-title">Scrap Equipment</h4>
                        <?php if (!empty($scrapData)): ?>
                        <div class="table-responsive">
                            <table id="scrapTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Equipment Code</th>
                                        <th>Reason</th>
                                        <th>Quantity</th>
                                        <th>Date Deleted</th> <!-- Added Date Deleted Column -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scrapData as $equipment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipment['eq_code'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($equipment['reason'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($equipment['sc_qte'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($equipment['date_deleted'] ?? ''); ?></td> <!-- Display date_deleted -->
                                        <td><a href='delete_equipment.php?id=<?php echo $equipment['id_scr']; ?>' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="alert alert-warning text-center alert-message">No scrap equipment found.</p>
                        <?php endif; ?>
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

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#scrapTable').DataTable({
        "pagingType": "simple_numbers",
        "searching": true,
        "info": false,
        "lengthChange": false,
        "pageLength": 10
    });
});
</script>
</body>
</html>
