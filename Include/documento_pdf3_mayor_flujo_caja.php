<?php
include_once('config.inc.php');
include_once(path(DIR_INCLUDE) . 'comun.lib.php');

$sesionId = isset($_GET['sesionId']) ? $_GET['sesionId'] : '';
if (!empty($sesionId) && session_status() !== PHP_SESSION_ACTIVE) {
    session_id($sesionId);
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$tcpdfPath = path(DIR_SISTEMA) . 'contabilidad_r_mayor_flujo_caja/reader/Classes/PHPExcel/Shared/PDF/tcpdf.php';
if (!file_exists($tcpdfPath)) {
    $tcpdfPath = dirname(__FILE__) . '/../contabilidad_r_mayor_flujo_caja/reader/Classes/PHPExcel/Shared/PDF/tcpdf.php';
}
if (!file_exists($tcpdfPath)) {
    http_response_code(500);
    echo 'No se encontró la librería TCPDF requerida para generar el PDF.';
    exit;
}
require_once($tcpdfPath);

$htmlBody = isset($_SESSION['pdf']) ? $_SESSION['pdf'] : '';
$htmlHeader = isset($_SESSION['pdf_header']) ? $_SESSION['pdf_header'] : '';
$html = '<style>
    body { font-family: Helvetica, Arial, sans-serif; font-size: 9pt; color: #000; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000; padding: 4px; font-size: 9pt; }
    .table { width: 100%; border-collapse: collapse; }
    .table-bordered td, .table-bordered th { border: 1px solid #000; }
    .table-striped tr:nth-child(even) { background-color: #f7f7f7; }
    .table-condensed td, .table-condensed th { padding: 3px; }
    .bg-primary { background-color: #e6e6e6; font-weight: bold; text-align: center; }
    .bg-info { background-color: #f2f2f2; font-weight: bold; }
    .report-header td { border: none; padding: 2px; }
</style>';
$html .= $htmlHeader . $htmlBody;

$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('REPORTENUEVOENCONTABILIDAD');
$pdf->SetAuthor('REPORTENUEVOENCONTABILIDAD');
$pdf->SetTitle('Mayor por Flujo de Caja');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 10);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 9);
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('mayor_flujo_caja.pdf', 'I');
