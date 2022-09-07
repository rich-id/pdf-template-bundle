<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Fetcher;

use RichId\PdfTemplateBundle\Domain\Constant;
use RichId\PdfTemplateBundle\Domain\Entity\PdfTemplateConfiguration;
use RichId\PdfTemplateBundle\Domain\Port\PdfTemplateRepositoryInterface;
use Symfony\Contracts\Service\Attribute\Required;

final class PdfTemplateFetcher
{
    #[Required]
    public PdfTemplateRepositoryInterface $pdfTemplateRepository;

    public function __invoke(string $slug): string
    {
        $configuration = $this->pdfTemplateRepository->getPdfTemplateConfigurationFor($slug);

        return $configuration instanceof PdfTemplateConfiguration ? $configuration->getValue() : Constant::DEFAULT_TEMPLATE;
    }
}
