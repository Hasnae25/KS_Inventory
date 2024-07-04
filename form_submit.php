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
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
    } else {
        $action = $_GET['action'];
    }
    $code = $_POST['code'] ?? $_GET['code'];
    $name = $_POST['name'] ?? null;
    $type = $_POST['type'] ?? null;
    $qte = $_POST['qte'] ?? null;
    $aff = $_POST['aff'] ?? null;
    $status = $_POST['status'] ?? null;

   
    try { 
    if ($action == 'add') {
        if (!empty($code) && !empty($name) && !empty($type) && !empty($qte) && !empty($aff) && !empty($status)) {
            // Check if the code already exists
            $checkStmt = $conn->prepare("SELECT COUNT(`st-code`) FROM ks_storage WHERE `st-code` = ?");
            $checkStmt->bind_param("s", $code);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            $checkStmt->close();

            if ($count > 0) {
                $response['message'] = "Error: Equipment code already exists!";
            } else {
                $stmt = $conn->prepare("INSERT INTO ks_storage (`st-code`, `st-name`, `st-type`, `st-qte`, `st-affectation`, `st-status`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $code, $name, $type, $qte, $aff, $status);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Equipment added successfully.";
                } else {
                    $response['message'] = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $response['message'] = "All fields are required!";
        }
    } elseif ($action == 'update') {
        if (!empty($code)) {
            $checkStmt = $conn->prepare("SELECT COUNT(`st-code`) FROM ks_storage WHERE `st-code` = ?");
            $checkStmt->bind_param("s", $code);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            $checkStmt->close();

            if ($count > 0) {
                if (!empty($name) && !empty($type) && !empty($qte) && !empty($aff) && !empty($status)) {
                    $stmt = $conn->prepare("UPDATE ks_storage SET `st-name` = ?, `st-type` = ?, `st-qte` = ?, `st-affectation` = ?, `st-status` = ? WHERE `st-code` = ?");
                    $stmt->bind_param("ssssss", $name, $type, $qte, $aff, $status, $code);

                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = "Equipment updated successfully.";
                    } else {
                        $response['message'] = "Error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $response['message'] = "All fields are required for updating a record!";
                }
            } else {
                $response['message'] = "Equipment code does not exist!";
            }
        } else {
            $response['message'] = "Please enter a code to update equipment.";
        }
    } elseif ($action == 'delete') {
        if (!empty($code)) {
            $stmt = $conn->prepare("DELETE FROM ks_storage WHERE `st-code` = ?");
            $stmt->bind_param("s", $code);
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Equipment deleted successfully.";
            } else {
                $response['message'] = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = "Please enter a code to delete equipment.";
        }
    }
    
} catch (Exception $e) {
    $response['message'] = 'Exception: ' . $e->getMessage();
}

// If it's an AJAX request, return JSON response
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    echo json_encode($response);
    exit();
}

// For normal form submissions, redirect and set session message
$_SESSION['message'] = $response['message'];
header("Location: Storage.php");
exit();
}