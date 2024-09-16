<?php
include('database.php');

$tableName = 'ks_storage';
$sql = sprintf("SELECT `st-code` AS code, `st-name` AS name, `st-type` AS type, `st-qte` AS quantity, `st-affectation` AS affectation, `st-status` AS status FROM `%s`", $tableName);
$result = $conn->query($sql);

if ($result === false) {
    echo "<tr><td colspan='6'>Error: " . $conn->error . "</td></tr>";
} else {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $quantity = htmlspecialchars($row['quantity']);
            $status = '';
            if ($quantity <= 10) {
                $status = 'bg-danger'; // Red
            } elseif ($quantity <= 50) {
                $status = 'bg-warning'; // Orange
            } else {
                $status = 'bg-success'; // Green
            }
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['code']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
            echo "<td class='quantity'>" . $quantity . " 
                    <button class='btn btn-sm btn-outline-secondary increment' data-code='" . htmlspecialchars($row['code']) . "'>+</button> 
                    <button class='btn btn-sm btn-outline-secondary decrement' data-code='" . htmlspecialchars($row['code']) . "'>-</button>
                  </td>";
            echo "<td class='affectation'>" . htmlspecialchars($row['affectation']) . " 
                    <button class='btn btn-sm btn-outline-secondary change-affectation' data-code='" . htmlspecialchars($row['code']) . "'><i class='fa fa-exchange-alt'></i></button>
                  </td>";
            echo "<td>
                    <div class='progress'>
                        <div class='progress-bar $status' role='progressbar' style='width: " . ($quantity / 100) * 100 . "%;' aria-valuenow='$quantity' aria-valuemin='0' aria-valuemax='100'></div>
                    </div>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No results found</td></tr>";
    }
}

$conn->close();
?>
