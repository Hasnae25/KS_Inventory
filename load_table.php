<?php
include 'database.php';

$tableName = 'ks_storage';
$sql = sprintf("SELECT 
                    `st-code` AS code, 
                    `st-name` AS name, 
                    `st-type` AS type, 
                    `st-qte` AS quantity, 
                    `st-affectation` AS affectation, 
                    `st-status` AS status 
                FROM `%s`", $tableName);

$result = $conn->query($sql);

if ($result === false) {
    echo "<tr><td colspan='6'>Error: " . $conn->error . "</td></tr>";
} else {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['code']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
            echo "<td>" . htmlspecialchars($row['affectation']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No results found</td></tr>";
    }
}

$conn->close();
?>
