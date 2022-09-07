<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Port;

use RichId\PdfTemplateBundle\Domain\Entity\PdfTemplateConfiguration;

interface PdfTemplateRepositoryInterface
{
    public function getPdfTemplateConfigurationFor(string $slug): ?PdfTemplateConfiguration;
}
