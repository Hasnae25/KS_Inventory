<?php
session_start();
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';

if ($action == 'update' && isset($_GET['code'])) {
    $code = $_GET['code'];
    $stmt = $conn->prepare("SELECT `st-name`, `st-type`, `st-qte`, `st-affectation`, `st-status` FROM ks_storage WHERE `st-code` = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->bind_result($name, $type, $qte, $aff, $status);
    $stmt->fetch();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Equipment</title>
    
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
</head>
<body>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Manage Equipment</h4>
            <p class="card-description">Use this form to add or update equipment details.</p>
            <?php if ($message): ?>
                <p style="color: red;"><?php echo $message; ?></p>
            <?php endif; ?>
            <?php if ($errorMessage): ?>
                <p style="color: red;"><?php echo $errorMessage; ?></p>
            <?php endif; ?>

            <?php if ($action == 'add'): ?>
                <form action="form_submit.php" method="post">
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
                    <button type="submit" class="btn btn-outline-primary btn-fw">Add Equipment</button>
                </form>
            <?php elseif ($action == 'update' && isset($code)): ?>
                <form action="form_submit.php" method="post">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="code" value="<?php echo htmlspecialchars($code); ?>">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($type); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="qte">Quantity</label>
                        <input type="number" class="form-control" id="qte" name="qte" value="<?php echo htmlspecialchars($qte); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="aff">Affectation</label>
                        <input type="text" class="form-control" id="aff" name="aff" value="<?php echo htmlspecialchars($aff); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" class="form-control" id="status" name="status" value="<?php echo htmlspecialchars($status); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-fw">Update Equipment</button>
                </form>
            <?php elseif ($action == 'delete' && isset($_GET['code'])): ?>
                <form action="form_submit.php" method="post">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="code" value="<?php echo htmlspecialchars($_GET['code']); ?>">
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($_GET['code']); ?>" required disabled>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason for Deletion</label>
                        <select class="form-control" id="reason" name="reason" required>
                            <option value="outofstock">Out of Stock</option>
                            <option value="no_longer_usable">No Longer Usable</option>
                            <option value="damaged">Damaged</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qte">Quantity</label>
                        <input type="number" class="form-control" id="qte" name="qte" placeholder="Enter quantity to delete" required>
                    </div>
                    <button type="submit" class="btn btn-outline-danger btn-fw">Delete Equipment</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
