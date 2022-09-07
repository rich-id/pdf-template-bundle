<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Infrastructure\Adapter;

use RichId\PdfTemplateBundle\Domain\Entity\PdfTemplateConfiguration;
use RichId\PdfTemplateBundle\Domain\Port\PdfTemplateRepositoryInterface;
use RichId\PdfTemplateBundle\Infrastructure\Repository\PdfTemplateConfigurationRepository;
use Symfony\Contracts\Service\Attribute\Required;

class PdfTemplateRepositoryAdapter implements PdfTemplateRepositoryInterface
{
    #[Required]
    public PdfTemplateConfigurationRepository $pdfTemplateConfigurationRepository;

    public function getPdfTemplateConfigurationFor(string $slug): ?PdfTemplateConfiguration
    {
        return $this->pdfTemplateConfigurationRepository->findOneBySlug($slug);
    }
}
