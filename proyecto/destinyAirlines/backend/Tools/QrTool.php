<?php
require_once ROOT_PATH . '/vendor/autoload.php';

class QrTool
{
    public function generarQR($data, $dataUri = true)
    {
        //'<img src="data:image/png;base64,' . base64_encode($imagenQR) . '" />';
        $qrCode = Endroid\QrCode\QrCode::create($data);
        $writer = new Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);
        $img = $result->getString();
        $imgBase64 = base64_encode($img);
        if ($dataUri) {
            return 'data:image/png;base64,' . $imgBase64;
        } else {
            return $imgBase64;
        }
    }
}
