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
                $_SESSION['message'] = "Error: Equipment code already exists!";
            } else {
                $stmt = $conn->prepare("INSERT INTO ks_storage (`st-code`, `st-name`, `st-type`, `st-qte`, `st-affectation`, `st-status`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $code, $name, $type, $qte, $aff, $status);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Equipment added successfully.";
                } else {
                    $_SESSION['message'] = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $_SESSION['message'] = "All fields are required!";
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
                        $_SESSION['message'] = "Equipment updated successfully.";
                    } else {
                        $_SESSION['message'] = "Error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $_SESSION['message'] = "All fields are required for updating a record!";
                }
            } else {
                $_SESSION['message'] = "Equipment code does not exist!";
            }
        } else {
            $_SESSION['message'] = "Please enter a code to update equipment.";
        }
    } elseif ($action == 'delete') {
        if (!empty($code)) {
            $stmt = $conn->prepare("DELETE FROM ks_storage WHERE `st-code` = ?");
            $stmt->bind_param("s", $code);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Equipment deleted successfully.";
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Please enter a code to delete equipment.";
        }
    }
    header("Location: Storage.php");
    exit();
}
?>