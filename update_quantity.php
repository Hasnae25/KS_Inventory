<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = isset($_POST['code']) ? $_POST['code'] : '';
    $change = isset($_POST['change']) ? intval($_POST['change']) : 0;

    if (!empty($code) && $change != 0) {
        // Get current quantity
        $sql = "SELECT `st-qte` FROM ks_storage WHERE `st-code` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentQuantity = intval($row['st-qte']);
            $newQuantity = $currentQuantity + $change;

            if ($newQuantity >= 0) {
                // Update the quantity
                $updateSql = "UPDATE ks_storage SET `st-qte` = ? WHERE `st-code` = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("is", $newQuantity, $code);

                if ($updateStmt->execute()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to update quantity']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Quantity cannot be negative']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Equipment not found']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

$conn->close();
?>
