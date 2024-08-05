<?php
require 'vendor/autoload.php'; // Utiliser l'autoloader de Composer

// Créez un nouvel objet TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Inventory List');
$pdf->SetSubject('Inventory List');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Définir les marges
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Définir les en-têtes et les pieds de page
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Ajouter une page
$pdf->AddPage();

// Définir le titre du document
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 15, 'Inventory List', 0, 1, 'C');

// Ajouter les en-têtes de tableau
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(20, 10, 'Code', 1);
$pdf->Cell(40, 10, 'Name', 1);
$pdf->Cell(30, 10, 'Type', 1);
$pdf->Cell(20, 10, 'Quantity', 1);
$pdf->Cell(30, 10, 'Affectation', 1);
$pdf->Cell(30, 10, 'Status', 1);
$pdf->Ln();

// Connectez-vous à la base de données et récupérez les données
include('database.php');
$sql = "SELECT `st-code`, `st-name`, `st-type`, `st-qte`, `st-affectation`, `st-status` FROM `ks_storage`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pdf->Cell(20, 10, $row['st-code'], 1);
        $pdf->Cell(40, 10, $row['st-name'], 1);
        $pdf->Cell(30, 10, $row['st-type'], 1);
        $pdf->Cell(20, 10, $row['st-qte'], 1);
        $pdf->Cell(30, 10, $row['st-affectation'], 1);
        $pdf->Cell(30, 10, $row['st-status'], 1);
        $pdf->Ln();
    }
}

// Fermer et sortir le fichier PDF
$pdf->Output('inventory.pdf', 'D');
exit;
?>
