<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Pdf\Trait;

use mikehaertl\pdftk\Pdf;

trait PdfMergerTrait
{
    use PdfTempFileTrait;

    /** @param string[] $sources */
    private function mergePdfs(array $sources): string
    {
        return $this->withTempDir(function (string $tempDir) use ($sources) {
            $pdf = new Pdf();
            $pdf->ignoreWarnings = true;

            foreach ($sources as $index => $source) {
                $pdf->addFile($this->copySource($source, $tempDir, $index + 1));
            }

            $result = $pdf->toString();

            if (\is_bool($result)) {
                throw new \Exception('Failed to generate pdf file');
            }

            return $result;
        });
    }
}
