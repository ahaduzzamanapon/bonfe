<?php
// Run once: php register_bengali_font.php
require_once __DIR__ . '/vendor/autoload.php';

$fontDir = __DIR__ . '/vendor/dompdf/dompdf/lib/fonts/';
$fontPath = __DIR__ . '/public/fonts/NotoSansBengali.ttf';
$fontName = 'NotoSansBengali';

// Copy font to dompdf fonts dir
copy($fontPath, $fontDir . $fontName . '.ttf');
echo "Copied font to: $fontDir" . $fontName . ".ttf\n";

// Register font
$dompdf = new \Dompdf\Dompdf();
$fontMetrics = new \Dompdf\FontMetrics($dompdf->getCanvas(), $dompdf->getOptions());

$fontMetrics->registerFont(
    ['family' => 'NotoSansBengali', 'weight' => 'normal', 'style' => 'normal'],
    $fontDir . $fontName . '.ttf'
);
echo "Font registered successfully.\n";
