<?php
session_start();
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';
header('Content-Type: application/json');


$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_GET['action'])) {
    $action = $_POST['action'] ?? $_GET['action'];
    $code = $_POST['code'] ?? $_GET['code'];
    $name = $_POST['name'] ?? null;
    $type = $_POST['type'] ?? null;
    $qte = $_POST['qte'] ?? null;
    $aff = $_POST['aff'] ?? null;
    $status = $_POST['status'] ?? null;
    $reason = $_POST['reason'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
   
    try {
        if ($action == 'add') {
            if (!empty($code) && !empty($name) && !empty($type) && !empty($qte) && !empty($aff)) {
                $checkStmt = $conn->prepare("SELECT COUNT(`st-code`) FROM ks_storage WHERE `st-code` = ?");
                if ($checkStmt === false) {
                    $response['message'] = "Error preparing the statement: " . $conn->error;
                    echo json_encode($response);
                    exit();
                }
                $checkStmt->bind_param("s", $code);
                $checkStmt->execute();
                $checkStmt->bind_result($count);
                $checkStmt->fetch();
                $checkStmt->close();

                if ($count > 0) {
                    $response['message'] = "Error: Equipment code already exists!";
                } else {
                    $stmt = $conn->prepare("INSERT INTO ks_storage (`st-code`, `st-name`, `st-type`, `st-qte`, `st-affectation`, `id_user`) VALUES (?, ?, ?, ?, ?, ?)");
                    if ($stmt === false) {
                        $response['message'] = "Error preparing the insert statement: " . $conn->error;
                        echo json_encode($response);
                        exit();
                    }
                    $id_user = $_SESSION['ID']; // Use the ID of the logged-in user
                    $stmt->bind_param("sssssi", $code, $name, $type, $qte, $aff, $id_user);
                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = "Equipment added successfully.";
                    } else {
                        $response['message'] = "Error executing the insert statement: " . $stmt->error;
                    }
                    $stmt->close();
                }
            } else {
                $response['message'] = "All fields are required!";
            }
        } elseif ($action == 'delete') {
            if (empty($code) || empty($reason) || empty($quantity)) {
                $response['message'] = 'Please fill in all fields';
                echo json_encode($response);
                exit();
            }
        
            // Récupérer l'équipement et la quantité actuelle
            $stmt = $conn->prepare("SELECT `st-code`, `st-qte` FROM ks_storage WHERE `st-code` = ?");
            if ($stmt === false) {
                $response['message'] = "Error preparing the select statement: " . $conn->error;
                echo json_encode($response);
                exit();
            }
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $stmt->store_result();
        
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($st_code, $qtee); // Bind to $st_code (full equipment code)
                $stmt->fetch();
                $stmt->close();
        
                // Vérifier si la quantité à supprimer est disponible
                if ($qtee >= $quantity) {
                    $new_quantity = $qtee - $quantity; // Calculer la nouvelle quantité
                    $user_id = $_SESSION['ID'];
                    
                    // Insérer dans la table ks_scrap (scrap log)
                    $scrapStmt = $conn->prepare("INSERT INTO ks_scrap (id_eq, id_user, sc_qte, reason, eq_code) VALUES (?, ?, ?, ?, ?)");
                    if ($scrapStmt === false) {
                        $response['message'] = "Error preparing the insert statement: " . $conn->error;
                        echo json_encode($response);
                        exit();
                    }
                    $scrapStmt->bind_param("iiiss", $st_code, $user_id, $quantity, $reason, $st_code); // Insert full code
                    if ($scrapStmt->execute()) {
                        $scrapStmt->close();
        
                        // Mettre à jour ou supprimer l'équipement selon la nouvelle quantité
                        if ($new_quantity > 0) {
                            // Mise à jour de la quantité restante
                            $updateStmt = $conn->prepare("UPDATE ks_storage SET `st-qte` = ? WHERE `st-code` = ?");
                            if ($updateStmt === false) {
                                $response['message'] = "Error preparing the update statement: " . $conn->error;
                                echo json_encode($response);
                                exit();
                            }
                            $updateStmt->bind_param("is", $new_quantity, $st_code);
                            if ($updateStmt->execute()) {
                                $response['success'] = true;
                                $response['message'] = 'Equipment quantity updated successfully';
                            } else {
                                $response['message'] = 'Error executing the update statement: ' . $updateStmt->error;
                            }
                            $updateStmt->close();
                        } else {
                            // Supprimer l'équipement si la nouvelle quantité est 0
                            $deleteStmt = $conn->prepare("DELETE FROM ks_storage WHERE `st-code` = ?");
                            if ($deleteStmt === false) {
                                $response['message'] = "Error preparing the delete statement: " . $conn->error;
                                echo json_encode($response);
                                exit();
                            }
                            $deleteStmt->bind_param("s", $st_code);
                            if ($deleteStmt->execute()) {
                                $response['success'] = true;
                                $response['message'] = 'Equipment deleted successfully';
                            } else {
                                $response['message'] = 'Error executing the delete statement: ' . $deleteStmt->error;
                            }
                            $deleteStmt->close();
                        }
                    } else {
                        $response['message'] = 'Error executing the insert statement: ' . $scrapStmt->error;
                    }
                } else {
                    $response['message'] = 'Not enough quantity to delete';
                }
            } else {
                $response['message'] = 'Equipment not found';
            }
        }
        

    } catch (Exception $e) {
        $response['message'] = 'Exception: ' . $e->getMessage();
    }

    echo json_encode($response);
    exit();
} else {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit();
}
?>
