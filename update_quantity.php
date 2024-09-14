<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    
    // Mise à jour de la quantité
    if (isset($_POST['change'])) {
        $change = $_POST['change'];

        // Récupérer la quantité actuelle
        $stmt = $conn->prepare("SELECT `st-qte` FROM ks_storage WHERE `st-code` = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->bind_result($currentQuantity);
        $stmt->fetch();
        $stmt->close();

        if ($currentQuantity !== null) {
            // Calculer la nouvelle quantité
            $newQuantity = $currentQuantity + $change;

            // Mettre à jour la quantité dans la base de données
            $stmt = $conn->prepare("UPDATE ks_storage SET `st-qte` = ? WHERE `st-code` = ?");
            $stmt->bind_param("is", $newQuantity, $code);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Quantity updated successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update quantity']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid equipment code']);
        }
    }

    // Changement d'affectation
    if (isset($_POST['change_affectation'])) {
        // Récupérer l'affectation actuelle
        $stmt = $conn->prepare("SELECT `st-affectation` FROM ks_storage WHERE `st-code` = ?");
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $stmt->bind_result($affectation);
        $stmt->fetch();
        $stmt->close();

        // Alterner entre "IT Storage" et "Rep"
        $newAffectation = ($affectation === 'IT Storage') ? 'Rep' : 'IT Storage';

        // Mettre à jour l'affectation dans la base de données
        $updateStmt = $conn->prepare("UPDATE ks_storage SET `st-affectation` = ? WHERE `st-code` = ?");
        $updateStmt->bind_param('ss', $newAffectation, $code);

        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'message' => "Affectation changed to '$newAffectation'"]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to change affectation']);
        }

        $updateStmt->close();
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
