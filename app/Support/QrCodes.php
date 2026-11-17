<?php

namespace App\Support;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

/**
 * Génération de QR Codes via endroid/qr-code.
 * PNG rendu par GD (extension gd), SVG vectoriel — pas d'Imagick requis.
 */
class QrCodes
{
    public static function png(string $contenu, int $taille = 400): string
    {
        return Builder::create()
            ->data($contenu)
            ->size($taille)
            ->margin(10)
            ->writer(new PngWriter)
            ->build()
            ->getString();
    }

    public static function svg(string $contenu, int $taille = 400): string
    {
        return Builder::create()
            ->data($contenu)
            ->size($taille)
            ->margin(10)
            ->writer(new SvgWriter)
            ->build()
            ->getString();
    }
}
