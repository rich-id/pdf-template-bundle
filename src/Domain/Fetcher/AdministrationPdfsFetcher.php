<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Fetcher;

use RichId\PdfTemplateBundle\Domain\Internal\InternalPdfManager;
use RichId\PdfTemplateBundle\Domain\Model\AdministrationPdfModel;
use Symfony\Contracts\Service\Attribute\Required;

final class AdministrationPdfsFetcher
{
    #[Required]
    public InternalPdfManager $internalPdfManager;

    #[Required]
    public PdfTemplateFetcher $pdfTemplateFetcher;

    /** @return AdministrationPdfModel[] */
    public function __invoke(): array
    {
        $models = [];

        foreach ($this->internalPdfManager->pdfs as $pdf) {
            if (!isset($models[$pdf->getPdfSlug()])) {
                $models[$pdf->getPdfSlug()] = AdministrationPdfModel::build(
                    $pdf->getPdfSlug(),
                    $pdf->getName(),
                    ($this->pdfTemplateFetcher)($pdf->getPdfSlug()),
                    $pdf::TEMPLATES
                );

                continue;
            }

            $models[$pdf->getPdfSlug()]->allowedValues = \array_unique(
                \array_merge($models[$pdf->getPdfSlug()]->allowedValues, $pdf::TEMPLATES)
            );
        }

        return \array_values($models);
    }
}
