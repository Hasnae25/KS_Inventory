<?php
session_start();
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

include('database.php');

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // Clear the session message after retrieving it

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
        .btn-custom {
            width: 120px;
            margin: 10px;
        }
        .btn-custom.add {
            background-color: #28a745;
            color: #fff;
        }
        .btn-custom.delete {
            background-color: #dc3545;
            color: #fff;
        }
        .btn-custom.export {
            background-color: #17a2b8;
            color: #fff;
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
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Storage Equipment</h4>
                            <div class="d-flex justify-content-start">
                                <button type="button" class="btn btn-custom add" id="add-button"> <i class="fa fa-plus-square-o"></i> Add</button>
                                <button type="button" class="btn btn-custom delete" id="delete-button"> <i class="fa fa-trash-o"></i> Delete</button>
                            </div>
                            <!-- Export buttons -->
                            <div class="d-flex justify-content-end m-3">
                                <button type="button" class="btn btn-custom export" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
                                <button type="button" class="btn btn-custom export" id="exportPDF"><i class="fa fa-file-pdf-o"></i> Export to PDF</button>
                            </div>
                            <div id="form-content"></div>
                            <div class="table-responsive">
                                <table id="storageTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Quantity</th>
                                            <th>Affectation</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $tableName = 'ks_storage';
                                        $sql = sprintf("SELECT `st-code` AS code, `st-name` AS name, `st-type` AS type, `st-qte` AS quantity, `st-affectation` AS affectation, `st-status` AS status FROM `%s`", $tableName);
                                        $result = $conn->query($sql);

                                        if ($result === false) {
                                            echo "<tr><td colspan='6'>Error: " . $conn->error . "</td></tr>";
                                        } else {
                                            if ($result->num_rows > 0) {
                                             
                                                while ($row = $result->fetch_assoc()) {
                                                    $quantity = htmlspecialchars($row['quantity']);
                                                    $status = '';
                                                    if ($quantity <= 10) {
                                                        $status = 'bg-danger'; // Red
                                                    } elseif ($quantity <= 50) {
                                                        $status = 'bg-warning'; // Orange
                                                    } else {
                                                        $status = 'bg-success'; // Green
                                                    }
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row['code']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                                    echo "<td class='quantity'>" . $quantity . " 
                                                            <button class='btn btn-sm btn-outline-secondary increment' data-code='" . htmlspecialchars($row['code']) . "'>+</button> 
                                                            <button class='btn btn-sm btn-outline-secondary decrement' data-code='" . htmlspecialchars($row['code']) . "'>-</button>
                                                          </td>";
                                                    echo "<td class='affectation'>" . htmlspecialchars($row['affectation']) . " 
                                                            <button class='btn btn-sm btn-outline-secondary change-affectation' data-code='" . htmlspecialchars($row['code']) . "'><i class='fa fa-exchange-alt'></i></button>
                                                          </td>";
                                                    echo "<td>
                                                            <div class='progress'>
                                                                <div class='progress-bar $status' role='progressbar' style='width: " . ($quantity / 100) * 100 . "%;' aria-valuenow='$quantity' aria-valuemin='0' aria-valuemax='100'></div>
                                                            </div>
                                                          </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6'>No results found</td></tr>";
                                            }
                                        }

                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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
<!-- container-scroller -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery and jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#storageTable').DataTable({
        "pagingType": "simple_numbers",
        "searching": true,
        "info": false,
        "lengthChange": false,
        "pageLength": 10,
        "language": {
            "paginate": {
                "previous": "<",
                "next": ">"
            },
            "search": "Search:"
        }
    });

    // Function to reload the table data without refreshing the page
    function loadTable() {
        $.ajax({
            url: 'load_table.php', // Create this PHP file to load the table data
            type: 'GET',
            success: function(data) {
                $('#storageTable').DataTable().clear().rows.add($(data)).draw();
            },
            error: function(xhr, status, error) {
                console.error('Error loading table data: ' + (xhr.responseText ? xhr.responseText : status));
            }
        });
    }

    // Add button click
    $('#add-button').on('click', function() {
        var formContent = `
            <form id="add-equipment-form" method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="code">Code</label>
                    <input type="text" class="form-control" id="code" name="code" required>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <input type="text" class="form-control" id="type" name="type" required>
                </div>
                <div class="form-group">
                    <label for="qte">Quantity</label>
                    <input type="number" class="form-control" id="qte" name="qte" required>
                </div>
                <div class="form-group">
                    <label for="aff">Affectation</label>
                    <input type="text" class="form-control" id="aff" name="aff" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" class="form-control" id="status" name="status" required>
                </div>
                <button type="submit" class="btn btn-outline-primary btn-fw">Add Equipment</button>
            </form>
        `;
        document.getElementById('form-content').innerHTML = formContent;
        document.getElementById('form-content').style.display = 'block';

        // Handle form submission via AJAX
        $('#add-equipment-form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            $.ajax({
                url: 'form_submit.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Display success message and reload table data
                        alert(response.message);
                        loadTable(); // Reload the table data
                        document.getElementById('form-content').innerHTML = ''; // Clear the form
                    } else {
                        // Display error message within the form content
                        document.getElementById('form-content').innerHTML = '<p style="color: red;">' + response.message + '</p>' + formContent;
                    }
                },
                error: function(xhr, status, error) {
                    // Display detailed error message
                    var errorMessage = 'An error occurred: ' + (xhr.responseText ? xhr.responseText : status);
                    document.getElementById('form-content').innerHTML = '<p style="color: red;">' + errorMessage + '</p>' + formContent;
                }
            });
        });
    });

    // Delete button click
    $('#delete-button').on('click', function() {
        $('#deleteModal').modal('show');
    });

    // Confirm delete button click
    $('#confirmDelete').on('click', function() {
        var code = $('#deleteCode').val();
        var reason = $('#deleteReason').val();
        var quantity = $('#deleteQuantity').val();

        if (code && reason && quantity) {
            $.ajax({
                url: 'form_submit.php',
                type: 'POST',
                data: {
                    action: 'delete',
                    code: code,
                    reason: reason,
                    quantity: quantity // Ensure quantity is sent
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        loadTable(); // Reload the table data
                    } else {
                        alert(response.message || 'An error occurred');
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while processing your request: ' + xhr.responseText);
                }
            });
            $('#deleteModal').modal('hide');
        } else {
            alert('Please fill in all fields');
        }
    });

    // Export buttons click
    $('#exportExcel').on('click', function() {
        window.location.href = 'export_excel.php';
    });

    $('#exportPDF').on('click', function() {
        window.location.href = 'export_pdf.php';
    });

    // Bind event listeners using event delegation to ensure they remain after updates
    $('#storageTable').on('click', '.increment', function() {
        var row = $(this).closest('tr');
        var code = $(this).data('code');
        updateQuantity(code, 1, row); // Increment quantity by 1
    });

    $('#storageTable').on('click', '.decrement', function() {
        var row = $(this).closest('tr');
        var code = $(this).data('code');
        updateQuantity(code, -1, row); // Decrement quantity by 1
    });

    $('#storageTable').on('click', '.change-affectation', function() {
        var row = $(this).closest('tr');
        var code = $(this).data('code');
        var currentAffectation = row.find('.affectation').text().trim();
        var newAffectation = (currentAffectation === 'IT Storage') ? 'Rep' : 'IT Storage';

        $.ajax({
            url: 'update_affectation.php',
            type: 'POST',
            data: {
                code: code,
                affectation: newAffectation
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadTable(); // Reload the table data
                } else {
                    alert(response.error || 'An error occurred');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while processing your request: ' + xhr.responseText);
            }
        });
    });

    function updateQuantity(code, change, row) {
        $.ajax({
            url: 'update_quantity.php',
            type: 'POST',
            data: {
                code: code,
                change: change
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadTable(); // Reload the table data
                } else {
                    alert(response.error || 'An error occurred');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while processing your request: ' + xhr.responseText);
            }
        });
    }
});

</script>

<!-- Modal for delete confirmation -->
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="deleteForm">
                    <div class="form-group">
                        <label for="deleteCode">Equipment Code:</label>
                        <input type="text" id="deleteCode" name="code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="deleteReason">Reason for Deletion:</label>
                        <select id="deleteReason" name="reason" class="form-control" required>
                            <option value="outofstock">Out of Stock</option>
                            <option value="no_longer_usable">No Longer Usable</option>
                            <option value="damaged">Damaged</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deleteQuantity">Quantity:</label>
                        <input type="number" id="deleteQuantity" name="quantity" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
