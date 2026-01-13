<?php
include_once('config.inc.php');
$sesionId = isset($_GET['sesionId']) ? $_GET['sesionId'] : '';
if (!empty($sesionId) && session_status() === PHP_SESSION_ACTIVE && session_id() !== $sesionId) {
    session_write_close();
    session_id($sesionId);
}
if (!empty($sesionId) && session_status() !== PHP_SESSION_ACTIVE) {
    session_id($sesionId);
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$tcpdfCandidates = array(
    DIR_SISTEMA . 'contabilidad_r_mayor_flujo_caja/reader/Classes/PHPExcel/Shared/PDF/tcpdf.php',
    DIR_MODULOS . 'contabilidad_r_mayor_flujo_caja/reader/Classes/PHPExcel/Shared/PDF/tcpdf.php',
    dirname(__FILE__) . '/../contabilidad_r_mayor_flujo_caja/reader/Classes/PHPExcel/Shared/PDF/tcpdf.php',
);
$tcpdfPath = '';
foreach ($tcpdfCandidates as $candidate) {
    if (file_exists($candidate)) {
        $tcpdfPath = $candidate;
        break;
    }
}
if (empty($tcpdfPath)) {
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
    th, td { padding: 2px 3px; font-size: 9pt; }
    .report-header td { border: none; padding: 2px; }
    .report-table td { border: none; }
    .report-table .bg-primary { background-color: #e6e6e6; font-weight: bold; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000; }
    .report-table .bg-info { font-weight: bold; }
    .report-table .report-saldo td { border-bottom: 1px solid #000; }
    .report-table .report-total td { border-top: 1px solid #000; font-weight: bold; }
    .table-condensed td, .table-condensed th { padding: 2px 3px; }
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

if (ob_get_length()) {
    ob_end_clean();
}
$pdf->Output('mayor_flujo_caja.pdf', 'I');
