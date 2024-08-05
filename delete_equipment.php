<?php
session_start();
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

include('database.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM ks_scrap WHERE id_scr = ?");
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "Record deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['message'] = "Invalid request.";
}

// Redirect back to scrap page
header("Location: scrap.php");
exit();
?>
