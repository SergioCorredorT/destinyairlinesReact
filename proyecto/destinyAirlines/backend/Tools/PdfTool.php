<?php
require_once './vendor/autoload.php';
class PdfTool
{
    public static function generatePdfDomPDF(string $html)
    {
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdf = $dompdf->output();
        return $pdf;
    }

    public static function generatePdfMPDF(string $html)
    {
        
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $pdf = $mpdf->Output('', 'S');

        return $pdf;
    }
    public static function generatePdfFromHtml(string $html)
    {
        if (extension_loaded('gd')) {
            //solo si está habilitada la extensión GD en php.ini, si no, solo será compatible un versión antigua de mpdf que a su vez no es compatible con php 8 o superior
            $pdf = self::generatePdfMPDF($html);
        } else {
            $pdf = self::generatePdfDomPDF($html);
        }
    
        return $pdf;
    }
}
