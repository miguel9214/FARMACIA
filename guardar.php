<?php
require 'lib/fpdf/fpdf.php';
require 'lib/fpdi/src/autoload.php';

use setasign\Fpdi\Fpdi;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filePath = $_POST['filePath'];
    $signatureData = $_POST['signatureData'];

    // Decodificar la firma (Base64)
    list(, $signatureBase64) = explode(',', $signatureData);
    $signature = base64_decode($signatureBase64);

    // Guardar la firma como una imagen temporal
    $signatureImage = 'firma.png';
    file_put_contents($signatureImage, $signature);

    // Cargar el PDF y agregar la firma
    $pdf = new FPDI();
    $pdf->AddPage();
    $pdf->setSourceFile($filePath);
    $template = $pdf->importPage(1);
    $pdf->useTemplate($template, 10, 10, 200);

    // Insertar la imagen de la firma
    $pdf->Image($signatureImage, 10, 250, 50, 20);

    // Guardar el PDF firmado
    $outputPath = 'uploads/firmado_' . basename($filePath);
    $pdf->Output($outputPath, 'F');

    echo "PDF firmado guardado: <a href='$outputPath'>Descargar PDF firmado</a>";
}
?>
