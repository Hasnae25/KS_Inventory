<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $change = $_POST['change'];

    // Fetch current quantity
    $stmt = $conn->prepare("SELECT `st-qte` FROM ks_storage WHERE `st-code` = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->bind_result($currentQuantity);
    $stmt->fetch();
    $stmt->close();

    if ($currentQuantity !== null) {
        // Calculate new quantity
        $newQuantity = $currentQuantity + $change;

        // Update quantity in database
        $stmt = $conn->prepare("UPDATE ks_storage SET `st-qte` = ? WHERE `st-code` = ?");
        $stmt->bind_param("is", $newQuantity, $code);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database update failed']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid equipment code']);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
