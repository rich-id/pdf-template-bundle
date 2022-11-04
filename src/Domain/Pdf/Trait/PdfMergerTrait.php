<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Pdf\Trait;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

trait PdfMergerTrait
{
    /** @param string[] $pdfs */
    private function mergePdfs(array $pdfs): string
    {
        $encoder = new Fpdi();

        foreach ($pdfs as $pdf) {
            $pageCount = $encoder->setSourceFile(StreamReader::createByString($pdf));

            for ($i = 1; $i <= $pageCount; $i++) {
                $tplidx = $encoder->importPage($i);
                $specs = $encoder->getTemplateSize($tplidx);

                if (\is_array($specs)) {
                    $encoder->addPage($specs['orientation'], [$specs['width'], $specs['height']]);
                }

                $encoder->useTemplate($tplidx);
            }
        }

        return $encoder->Output('S');
    }
}
