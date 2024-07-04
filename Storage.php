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



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KsInventroy</title>
    
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- plugins:jquery -->
    <link rel="stylesheet" href="assets/vendors/jquery/jquery-ui.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="assets/images/kroschu1.png" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
        }
        .popup-form {
            display: none;
        }
    </style>
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      <!-- partial:../../partials/_navbar.html -->
      <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
          <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
              <span class="icon-menu"></span>
            </button>
          </div>
          <div>
            <a class="navbar-brand brand-logo" href="index.php">
              <img src="assets/images/logo.png" alt="logo" />
            </a>
            
          </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top">
          <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
              <h1 class="welcome-text">Inventory Management, <span class="text-black fw-bold">Storage</span></h1>
              <h3 class="welcome-sub-text">Storage Inventory </h3>

            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
              <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="img-xs rounded-circle" src="assets/images/faces/face8.png" alt="Profile image"> </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center">
                  <img class="img-md rounded-circle" src="assets/images/faces/face8.png" alt="Profile image">
                  <p class="mb-1 mt-3 fw-semibold"><?php echo htmlspecialchars($_SESSION['FirstName'] . ' ' . $_SESSION['LastName']); ?></p>
                  <p class="fw-light text-muted mb-0"><?php echo htmlspecialchars($_SESSION['Email']); ?></p>
                </div>
                  <a class="dropdown-item"  href="profile.php"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile </a>
                  <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i> FAQ</a>
                  <a class="dropdown-item" href="logout.php"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Storage.php">
                <i class="fa fa-database menu-icon"></i>
                <span class="menu-title">Storage</span>
              </a>
            </li>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
          <div class="col-16 grid-margin">
                  <div class="card-body">
                  <h4 class="card-title">Storage Equipment </h4>
                  <button type="button" class="btn btn-inverse-success btn-fw" id="add-button"> <i class="fa fa-plus-square-o"></i> Add</button>
                  <button type="button" class="btn btn-inverse-danger btn-fw" id="delete-button"> <i class="fa fa-trash-o"></i> Delete</button>
                  <button type="button" class="btn btn-inverse-primary btn-fw" id="update-button"> <i class="ti-reload btn-icon-prepend"></i> Update</button> 
                    <p class="card-description"><code></code>
                     <!-- Popup form -->
                     
                                <div id="form-content"></div>
                            </div>
                    </p>
                    <?php if ($message): ?>
                <p style="color: red;"><?php echo $message; ?></p>
              <?php endif; ?>
                    <div class="table-responsive">
                      <table class="table table-striped">
                        
                        <thead>
                          <tr>
                            <th><h1  class="badge badge-opacity-warning me-40">Code </th>
                            <th> <h1  class="badge badge-opacity-warning me-40"> Name </th>
                            <th> <h1  class="badge badge-opacity-warning me-40">Type </th>
                            <th> <h1  class="badge badge-opacity-warning me-40">Quantite </th>
                            <th> <h1  class="badge badge-opacity-warning me-40">Affectation</th>
                            <th> <h1  class="badge badge-opacity-warning me-40">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                      // Requête SQL pour récupérer les données de la table
                      $sql = "SELECT `st-code`, `st-name`, `st-type`, `st-qte`, `st-affectation`, `st-status` FROM `ks_storage`";

                      $result = $conn->query($sql);

                      if ($result->num_rows > 0) {
                          // Output data of each row
                          while($row = $result->fetch_assoc()) {
                              echo "<tr>";
                              echo "<td>" . $row['st-code'] . "</td>";
                              echo "<td>" . $row['st-name'] . "</td>";
                              echo "<td>" . $row['st-type'] . "</td>";
                              echo "<td>" . $row['st-qte'] . "</td>";
                              echo "<td>" . $row['st-affectation'] . "</td>";
                              echo "<td>";
                              // Exemple de barre de progression basée sur st-status
                              $progress = intval($row['st-qte']); // Assurez-vous que st-status est un pourcentage (0-100)
                              echo '<div class="progress">';
                              echo '<div class="progress-bar bg-success" role="progressbar" style="width: ' . $progress . '%" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100"></div>';
                              echo '</div>';
                              echo "</td>";
                              echo "</tr>";
                          }
                      } else {
                          echo "0 results";
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
          </div>
          <div id="deleteModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Delete Equipment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <p>Please enter the code of the equipment you want to delete:</p>
                <input type="text" id="deleteInput" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div id="updateModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Equipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please enter the code of the equipment you want to update:</p>
                <input type="text" id="updateInput" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmUpdate">Update</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
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
    <script>

        document.getElementById('add-button').addEventListener('click', function() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "form.php?action=add", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('form-content').innerHTML = xhr.responseText;
                    document.getElementById('form-content').style.display = 'block';
                }
            }
            xhr.send();
        });

       $(document).ready(function() {
    // Delete button click
    $('#delete-button').on('click', function() {
        $('#deleteModal').modal('show');
    });
    $('#confirmDelete').on('click', function() {
        var selectedCode = $('#deleteInput').val();
        if (selectedCode) {
            $.ajax({
                url: 'form_submit.php',
                type: 'GET',
                data: { action: 'delete', code: selectedCode },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Request failed: ' + error);
                }
            });
        }
    });


    $('#update-button').on('click', function() {
        $('#updateModal').modal('show');
    });

// Confirm update button click
   // Confirm update button click
   $('#confirmUpdate').on('click', function() {
        var selectedCode = $('#updateInput').val();
        if (selectedCode) {
            $.ajax({
                url: 'form_submit.php',
                type: 'GET',
                data: { action: 'update', code: selectedCode },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Request failed: ' + error);
                }
            });
        }
    });
});
        
</script>

</body>
</html>