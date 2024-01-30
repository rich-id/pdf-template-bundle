<?php

namespace RichId\PdfTemplateBundle\Domain\Pdf\Trait;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\Service\Attribute\Required;

trait PdfTempFileTrait
{
    #[Required]
    public Filesystem $filesystem;

    /**
     * @template T
     * @param callable(string $tempDir): T $f
     * @return T
     */
    private function withTempDir(callable $f): mixed
    {
        $tempDir = $this->filesystem->tempnam(\sys_get_temp_dir(), 'pdf-merger-');
        $this->filesystem->remove($tempDir);

        $this->filesystem->mkdir($tempDir);
        $result = $f($tempDir);
        $this->filesystem->remove($tempDir);

        return $result;
    }

    private function copySource(string $source, string $tempDir, int $index): string
    {
        $tempFilePath = \sprintf('%s/pdf-merger-file-%d', $tempDir, $index);
        $result = \file_put_contents($tempFilePath, $source);

        if (\is_bool($result)) {
            throw new \Exception('An error occurred when create temporary file.');
        }

        return $tempFilePath;
    }
}
