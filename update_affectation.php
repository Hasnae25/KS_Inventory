<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $newAffectation = $_POST['affectation'];

    $stmt = $conn->prepare("UPDATE ks_storage SET `st-affectation` = ? WHERE `st-code` = ?");
    $stmt->bind_param('ss', $newAffectation, $code);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
