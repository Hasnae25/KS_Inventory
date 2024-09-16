<?php
// Démarrer la mise en tampon de sortie pour éviter l'envoi de contenu avant les en-têtes
ob_start();

require 'vendor/autoload.php'; // Utiliser l'autoloader de Composer
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Créez un nouvel objet Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Ajoutez des données à la feuille
$sheet->setCellValue('A1', 'Code');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Type');
$sheet->setCellValue('D1', 'Quantity');
$sheet->setCellValue('E1', 'Affectation');
$sheet->setCellValue('F1', 'Status');

// Connectez-vous à la base de données et récupérez les données
include('database.php');
$sql = "SELECT `st-code`, `st-name`, `st-type`, `st-qte`, `st-affectation`, `st-status` FROM `ks_storage`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rowIndex = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowIndex, $row['st-code']);
        $sheet->setCellValue('B' . $rowIndex, $row['st-name']);
        $sheet->setCellValue('C' . $rowIndex, $row['st-type']);
        $sheet->setCellValue('D' . $rowIndex, $row['st-qte']);
        $sheet->setCellValue('E' . $rowIndex, $row['st-affectation']);
        $sheet->setCellValue('F' . $rowIndex, $row['st-status']);
        $rowIndex++;
    }
}

// Définir les en-têtes HTTP pour le téléchargement
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="inventory.xlsx"');
header('Cache-Control: max-age=0');

// Écrire le fichier et l'envoyer au navigateur
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Terminer la mise en tampon de sortie
ob_end_flush();
exit;
