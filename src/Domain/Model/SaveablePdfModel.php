<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Model;

final class SaveablePdfModel
{
    private string $filesystemName;
    private string $fileName;
    private bool $canSave;
    private bool $canForceNewGeneration;

    public function getFilesystemName(): string
    {
        return $this->filesystemName;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function canSave(): bool
    {
        return $this->canSave;
    }
    public function canForceNewGeneration(): bool
    {
        return $this->canForceNewGeneration;
    }

    public static function create(string $filesystemName, string $fileName, bool $canSave = true, bool $canForceNewGeneration = false): self
    {
        $model = new self();

        $model->filesystemName = $filesystemName;
        $model->fileName = $fileName;
        $model->canSave = $canSave;
        $model->canForceNewGeneration = $canForceNewGeneration;

        return $model;
    }
}
