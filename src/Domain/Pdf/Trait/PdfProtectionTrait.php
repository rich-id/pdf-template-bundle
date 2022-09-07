<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Pdf\Trait;

use setasign\Fpdi\PdfParser\StreamReader;
use setasign\FpdiProtection\FpdiProtection;

trait PdfProtectionTrait
{
    private function internalProtectPdf(string $pdf): string
    {
        $encoder = new FpdiProtection();
        $pageCount = $encoder->setSourceFile(StreamReader::createByString($pdf));

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplidx = $encoder->importPage($i);
            $specs = $encoder->getTemplateSize($tplidx);
            $encoder->addPage($specs['orientation'], [$specs['width'], $specs['height']]);
            $encoder->useTemplate($tplidx);
        }

        $encoder->setProtection([FpdiProtection::PERM_PRINT, FpdiProtection::PERM_DIGITAL_PRINT]);
        return $encoder->Output('S');
    }
}
