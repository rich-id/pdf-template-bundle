<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Pdf\Trait;

use mikehaertl\pdftk\Pdf;

trait PdfProtectionTrait
{
    use PdfTempFileTrait;

    private function internalProtectPdf(string $source): string
    {
        return $this->withTempDir(function (string $tempDir) use ($source) {
            $pdf = new Pdf();
            $pdf->ignoreWarnings = true;

            $pdf->addFile($this->copySource($source, $tempDir, 0));

            $pdf->setPassword(self::randomPassword());
            $pdf->allow('Printing DegradedPrinting');

            $result = $pdf->toString();

            if (\is_bool($result)) {
                throw new \Exception('Failed to generate pdf file');
            }

            return $result;
        });
    }

    private static function randomPassword(int $length = 32): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}
