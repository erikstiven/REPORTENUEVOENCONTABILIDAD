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
$htmlBody = preg_replace(
    '/(<tr>\\s*<td>\\s*<\\/td>\\s*<td colspan="7")(>\\s*)/i',
    '$1 style="text-align:left; padding-left:2px;"$2<b>Mes:</b> ',
    $htmlBody
);
$htmlBody = str_replace(
    'class="bg-primary"',
    'class="bg-primary" style="border:1px solid #000;"',
    $htmlBody
);
$htmlHeader = isset($_SESSION['pdf_header']) ? $_SESSION['pdf_header'] : '';
$html = '<style>
    body { font-family: Arial, Helvetica, sans-serif; font-size: 8pt; color: #000; }
    table { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 2px; font-size: 8pt; }
    .report-header td { border: none; padding: 1px 2px; font-family: Arial, Helvetica, sans-serif; line-height: 1.15; }
    .report-meta td { border: none; padding: 0 2px; }
    .report-table td { border: none; }
    .report-table .report-head { font-weight: normal; border: 1px solid #000; font-size: 9pt; }
    .report-head-left { text-align: left; }
    .report-head-center { text-align: center; }
    .report-saldo,
    .report-saldo td { font-weight: bold !important; }
    .bg-info { font-weight: bold; }
    .report-total td { border-top: 1px solid #000; font-weight: normal; }
    .table-condensed td, .table-condensed th { padding: 1px 2px; }
</style>';
$html .= $htmlHeader . $htmlBody;

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('REPORTENUEVOENCONTABILIDAD');
$pdf->SetAuthor('REPORTENUEVOENCONTABILIDAD');
$pdf->SetTitle('Mayor por Flujo de Caja');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 10);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 8);
$pdf->writeHTML($html, true, false, true, false, '');

if (ob_get_length()) {
    ob_end_clean();
}
$pdf->Output('mayor_flujo_caja.pdf', 'I');
