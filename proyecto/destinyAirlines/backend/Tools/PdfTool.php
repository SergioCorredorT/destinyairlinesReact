<?php
require_once ROOT_PATH . '/vendor/autoload.php';
class PdfTool
{
    public static function generatePdfFromHtml(string $html, bool $isVertical = true): string|null
    {
        $pdf = self::generatePdfDomPDF($html, $isVertical);

        return $pdf;
    }

    public static function generatePdfDomPDF(string $html, bool $isVertical = true): string|null
    {
        //No entiende display grid, pero no hay mejor librería pdf
        $dompdf = new \Dompdf\Dompdf();
        // Establecer el tamaño y la orientación del papel
        $orientation = $isVertical ? 'portrait' : 'landscape';
        $dompdf->setPaper('A4', $orientation);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdf = $dompdf->output();
        return $pdf;
    }
}
