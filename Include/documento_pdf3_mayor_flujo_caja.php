<?php
if (isset($_GET['sesionId'])) {
    session_id($_GET['sesionId']);
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$html = isset($_SESSION['pdf']) ? $_SESSION['pdf'] : '';
$headerData = isset($_SESSION['pdf_header']) ? $_SESSION['pdf_header'] : array();

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

class ReporteMayorFlujoCajaPDF extends TCPDF {
    public $headerData = array();

    public function Header() {
        $empresa = isset($this->headerData['empresa']) ? $this->headerData['empresa'] : '';
        $direccion = isset($this->headerData['direccion']) ? $this->headerData['direccion'] : '';
        $ruc = isset($this->headerData['ruc']) ? $this->headerData['ruc'] : '';
        $titulo = isset($this->headerData['titulo']) ? $this->headerData['titulo'] : 'MAYOR POR FLUJO DE CAJA';
        $fecha = isset($this->headerData['fecha']) ? $this->headerData['fecha'] : '';
        $hora = isset($this->headerData['hora']) ? $this->headerData['hora'] : '';
        $desde = isset($this->headerData['desde']) ? $this->headerData['desde'] : '';
        $hasta = isset($this->headerData['hasta']) ? $this->headerData['hasta'] : '';

        $this->SetFont('helvetica', 'B', 10);
        if ($empresa !== '') {
            $this->Cell(0, 5, $empresa, 0, 1, 'C');
        }
        $this->SetFont('helvetica', '', 8);
        if ($direccion !== '') {
            $this->Cell(0, 4, $direccion, 0, 1, 'C');
        }
        if ($ruc !== '') {
            $this->Cell(0, 4, 'RUC: '.$ruc, 0, 1, 'C');
        }
        $this->Ln(1);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 4, $titulo, 0, 1, 'C');
        $this->Ln(1);
        $this->SetFont('helvetica', '', 8);
        $this->Cell(35, 4, 'Fecha: '.$fecha, 0, 0, 'L');
        $this->Cell(30, 4, 'Hora: '.$hora, 0, 0, 'L');
        $this->Cell(40, 4, 'Desde: '.$desde, 0, 0, 'L');
        $this->Cell(40, 4, 'Hasta: '.$hasta, 0, 0, 'L');
        $this->Cell(0, 4, 'Pag '.$this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, 1, 'R');
        $this->Ln(2);
    }
}

$pdf = new ReporteMayorFlujoCajaPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->headerData = $headerData;
$pdf->SetCreator('Reporte');
$pdf->SetAuthor('Sistema');
$pdf->SetTitle('Reporte');
$pdf->SetPrintHeader(true);
$pdf->SetPrintFooter(false);
$pdf->SetMargins(10, 30, 10);
$pdf->SetAutoPageBreak(true, 12);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 7);
$pdf->IncludeJS('print(true);');
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reporte.pdf', 'I');
