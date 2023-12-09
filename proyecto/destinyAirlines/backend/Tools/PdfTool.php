<?php
require_once './vendor/autoload.php';
class PdfTool
{
    public static function generatePdfFromHtml(string $html, $isVertical = true)
    {
        //DESCARTADO extensión mpdf PORQUE OCUPA CASI 100 Mg a diferencia de Dompdf con 8 Mg, así que prefiero sin imagen en la invoice pero más ligereza en la aplicación
        //solo si está habilitada la extensión GD en php.ini, si no, solo será compatible un versión antigua de mpdf que a su vez no es compatible con php 8 o superior
        $pdf = self::generatePdfDomPDF($html, $isVertical);
        //$pdf = self::generatePdfWkHTMLToPDF($html);
        //$pdf = self::generatePdfMPDF($html);
        //$pdf = self::generatePdfHTML2PDF($html);

        return $pdf;
    }

    public static function generatePdfDomPDF(string $html, bool $isVertical = true)
{
    //No entiende display grid
    $dompdf = new \Dompdf\Dompdf();
    // Establecer el tamaño y la orientación del papel
    $orientation = $isVertical ? 'portrait' : 'landscape';
    $dompdf->setPaper('A4', $orientation);
    $dompdf->loadHtml($html);
    $dompdf->render();
    $pdf = $dompdf->output();
    return $pdf;
}


/*
    public static function generatePdfWkHTMLToPDF(string $html)
    {
        //Devuelve un pdf de 0 bytes
        // Crear una instancia de Pdf
        $pdf = new  mikehaertl\wkhtmlto\Pdf([
            'no-outline',         // Make Chrome not complain
            'margin-top'    => 0,
            'margin-right'  => 0,
            'margin-bottom' => 0,
            'margin-left'   => 0,

            // Default page options
            'disable-smart-shrinking',
            //'user-style-sheet' => '/path/to/pdf.css',
        ]);

        // Establecer el contenido HTML
        $pdf->addPage($html);

        // Generar el PDF y guardarlo en una variable
        $pdfContent = $pdf->toString();
        if ($pdf->getError()) {
            error_log('PDF error: ' . $pdf->getError());
        }

        return $pdfContent;
    }
*/
/*
    public static function generatePdfHTML2PDF(string $html)
    {
        //DESCARTADO porque no renderiza bien las imágenes ni algunas etiquetas que dompdf sí, como header, main y footer
        // Crear una instancia de HTML2PDF
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'es');

        // Escribir el contenido HTML
        $html2pdf->writeHTML($html);

        // Generar el PDF y guardarlo en una variable
        $pdf = $html2pdf->output('', 'S');

        return $pdf;
    }
*/
/*
    public static function generatePdfMPDF(string $html)
    {
        //Esta opción pesa mucho (100 Mg) y se ve igual que con dompdf
        // Crear una instancia de mPDF con orientación horizontal
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);

        $mpdf->WriteHTML($html);
        $pdf = $mpdf->Output('', 'S');

        return $pdf;
    }
*/
}
