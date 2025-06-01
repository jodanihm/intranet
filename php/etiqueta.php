<?php
require_once('../php/tcpdf/tcpdf.php');

$id = $_GET['id'] ?? 'N/A';
$paciente = $_GET['paciente'] ?? 'Sin nombre';
$dureza = $_GET['dureza'] ?? 'No indicada';
$forro = $_GET['forro'] ?? 'No definido';

$pdf = new TCPDF('L', 'mm', array(50, 30), true, 'UTF-8', false);
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false, 0);
$pdf->AddPage();

// Fuente general
$pdf->SetFont('helvetica', '', 10);

// Id más arriba
$pdf->SetY(4);
$pdf->SetX(5);
$pdf->MultiCell(40, 5, "Id: $id", 0, 'C', false);

// Paciente en negrita
$pdf->SetY(12);
$pdf->SetX(5);
$pdf->SetFont('helvetica', 'B', 10); // B de bold
$pdf->MultiCell(40, 5, "Paciente: $paciente", 0, 'L', false);

// Volver a fuente normal
$pdf->SetFont('helvetica', '', 10);
$pdf->SetX(5);
$pdf->MultiCell(40, 5, "Dureza: $dureza", 0, 'L', false);

$pdf->SetX(5);
$pdf->MultiCell(40, 5, "Forro: " . substr($forro, 0, 50), 0, 'L', false);

$pdf->Output('etiqueta_' . $id . '.pdf', 'I');