<?php
if (isset($_GET['sesionId'])) {
    session_id($_GET['sesionId']);
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$html = isset($_SESSION['pdf']) ? $_SESSION['pdf'] : '';

if ($html === '') {
    header('Content-Type: text/html; charset=UTF-8');
    echo 'No hay contenido disponible para imprimir.';
    exit;
}

$tcpdfPath = __DIR__ . '/../contabilidad_r_mayor_flujo_caja/reader/Classes/PHPExcel/Shared/PDF/tcpdf.php';
if (!file_exists($tcpdfPath)) {
    header('Content-Type: text/html; charset=UTF-8');
    echo $html;
    exit;
}

require_once $tcpdfPath;

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Reporte');
$pdf->SetAuthor('Sistema');
$pdf->SetTitle('Reporte');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 9);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reporte.pdf', 'I');
