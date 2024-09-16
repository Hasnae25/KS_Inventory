<?php
session_start();
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the session
$first_name = htmlspecialchars($_SESSION['FirstName']);
$last_name = htmlspecialchars($_SESSION['LastName']);
$email = htmlspecialchars($_SESSION['Email']);
$roles_id = $_SESSION['roles_id'];

// Database connection
$servername = "db"; // Change this from 'localhost' to 'db'
$username = "root";
$password = "rootpassword"; // Make sure this matches the password set in docker-compose.yml
$dbname = "ks";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch equipment data from the database
$sql = "SELECT `st-code`, `st-qte` FROM `ks_storage`";
$result = $conn->query($sql);

$equipmentData = [];
$totalQuantity = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $equipmentData[] = $row;
        $totalQuantity += $row['st-qte'];
    }
} else {
    echo "Error fetching equipment data: " . $conn->error;
}
// Fetch equipment with quantity under 10
$sql = "SELECT `st-code`, `st-type`, `st-qte` FROM `ks_storage` WHERE `st-qte` < 10";
$result = $conn->query($sql);

$lowStockData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lowStockData[] = $row;
    }
}

// Fetch sum of equipment types by affectation for IT Storage, rep, and Rep only
$affectationSql = "SELECT `st-affectation`, SUM(`st-qte`) as total_quantity 
                   FROM `ks_storage` 
                   WHERE `st-affectation` IN ('IT Storage', 'rep', 'Rep') 
                   GROUP BY `st-affectation`";
$affectationResult = $conn->query($affectationSql);

$affectationData = [];
if ($affectationResult && $affectationResult->num_rows > 0) {
    while ($row = $affectationResult->fetch_assoc()) {
        $affectationData[] = $row;
    }
} else {
    echo "Error fetching affectation data: " . $conn->error;
}

// Fetch scrap data
$scrapSql = "SELECT `reason`, SUM(`sc_qte`) as total_quantity FROM `ks_scrap` GROUP BY `reason`";
$scrapResult = $conn->query($scrapSql);

$scrapData = [];
if ($scrapResult && $scrapResult->num_rows > 0) {
    while ($row = $scrapResult->fetch_assoc()) {
        $scrapData[] = $row;
    }
} else {
    echo "Error fetching scrap data: " . $conn->error;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KSInventory</title>
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
     <!-- Bootstrap 5 CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- DataTables CSS -->
   <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="assets/js/select.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/kroschu1.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1"></script>

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
        <div class="main-panel">
                <div class="row">
                    <div class="col-md-3 col-xl-3 summary-card">
                        <i class="icon equipment fa fa-box"></i>
                        <h4>Total Equipments</h4>
                        <p><?php echo count($equipmentData); ?></p>
                    </div>
                    <div class="col-md-3 col-xl-3 summary-card">
                        <i class="icon quantity fa fa-balance-scale"></i>
                        <h4>Total Quantity</h4>
                        <p><?php echo $totalQuantity; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card card-custom">
                            <div class="card-body">
                                <h4 class="card-title">Quantities of Equipment </h4>
                                <div style="height: 400px;">
                                    <canvas id="equipmentChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card card-custom">
                            <div class="card-body">
                                <h4 class="card-title">Equipment Count by Affectation</h4>
                                <div style="height: 400px;">
                                    <canvas id="affectationChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card card-custom">
                            <div class="card-body">
                                <h4 class="card-title">Scrap Equipment Report</h4>
                                <div style="height: 400px;">
                                    <canvas id="scrapChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card card-custom">
                            <div class="card-body">
                                <h4 class="card-title">Inventory Movement</h4>
                                <div style="height: 400px;">
                                    <canvas id="inventoryMovementChart"></canvas>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/vendors/js/vendor.bundle.base.js"></script>
<script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="assets/vendors/chart.js/chart.umd.js"></script>
<script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
<script src="assets/js/off-canvas.js"></script>
<script src="assets/js/template.js"></script>
<script src="assets/js/settings.js"></script>
<script src="assets/js/hoverable-collapse.js"></script>
<script src="assets/js/todolist.js"></script>
<script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
<script src="assets/js/dashboard.js"></script>

<script>


    // Get equipment data from PHP
    const equipmentData = <?php echo json_encode($equipmentData); ?>;
    const affectationData = <?php echo json_encode($affectationData); ?>;
    const scrapData = <?php echo json_encode($scrapData); ?>;

    // Prepare data for Chart.js
    const codeLabels = equipmentData.map(item => item['st-code']);
    const quantities = equipmentData.map(item => item['st-qte']);
    const affectationLabels = affectationData.map(item => item['st-affectation']);
    const affectationQuantities = affectationData.map(item => item['total_quantity']);
    const scrapLabels = scrapData.map(item => item['reason']);
    const scrapQuantities = scrapData.map(item => item['total_quantity']);

    // Custom color palette
    const customColors = [
        '#8E44AD', '#9B59B6', '#BB8FCE', '#D2B4DE', '#E8DAEF', 
        '#F4ECF7', '#5B2C6F', '#7D3C98', '#6C3483', '#F8F9F9'
    ];

    // Check for negative quantities and set alert
    let annotations = [];
    quantities.forEach((quantity, index) => {
        if (quantity < 10) {
            customColors[index] = 'rgba(255, 99, 132, 0.2)';
            annotations.push({
                type: 'label',
                xValue: codeLabels[index],
                yValue: quantity,
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                content: ['Negative Stock', 'Code: ' + codeLabels[index]],
                position: 'center'
            });
        }
    });

    // Equipment Chart
    const ctxEquipment = document.getElementById('equipmentChart').getContext('2d');
    const equipmentChart = new Chart(ctxEquipment, {
        type: 'bar',
        data: {
            labels: codeLabels,
            datasets: [{
                label: 'Quantities',
                data: quantities,
                backgroundColor: customColors.slice(0, codeLabels.length),
                borderColor: '#FFFFFF',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                },
                annotation: {
                    annotations: annotations
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });

    // Affectation Chart
    const ctxAffectation = document.getElementById('affectationChart').getContext('2d');
    const affectationChart = new Chart(ctxAffectation, {
        type: 'pie',
        data: {
            labels: affectationLabels,
            datasets: [{
                label: 'Quantities by Affectation',
                data: affectationQuantities,
                backgroundColor: customColors.slice(0, affectationLabels.length),
                borderColor: '#FFFFFF',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

    // Inventory Movement Chart
    const ctxInventoryMovement = document.getElementById('inventoryMovementChart').getContext('2d');
    const inventoryMovementChart = new Chart(ctxInventoryMovement, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
                {
                    label: 'Added',
                    data: [120, 190, 30, 50, 20, 40, 70, 90, 100, 80, 60, 110],
                    backgroundColor: '#7D3C98',
                    borderColor: '#7D3C98',
                    borderWidth: 1
                },
                {
                    label: 'Deleted',
                    data: [20, 30, 200, 50, 10, 70, 30, 20, 50, 40, 60, 10],
                    backgroundColor: '#F4ECF7',
                    borderColor: '#F4ECF7',
                    borderWidth: 1
                },
                {
                    label: 'Updated',
                    data: [30, 100, 130, 150, 220, 110, 90, 60, 100, 70, 80, 120],
                    backgroundColor: '#9B59B6',
                    borderColor: '#9B59B6',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    stacked: true
                },
                y: {
                    beginAtZero: true,
                    stacked: true
                }
            }
        }
    });

    // Scrap Chart
    const ctxScrap = document.getElementById('scrapChart').getContext('2d');
    const scrapChart = new Chart(ctxScrap, {
        type: 'line',
        data: {
            labels: scrapLabels,
            datasets: [{
                label: 'Scrap Quantities',
                data: scrapQuantities,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4 // Smooth curves
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: false // Remove vertical grid lines
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 10
                    },
                    grid: {
                        borderDash: [10, 10] // Dashed grid lines
                    }
                }
            }
        }
    });
</script>
</body>
</html>
