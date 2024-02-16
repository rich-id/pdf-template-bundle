<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Internal;

use RichId\PdfTemplateBundle\Domain\Fetcher\PdfTemplateFetcher;
use RichId\PdfTemplateBundle\Domain\Pdf\AbstractPdf;
use Symfony\Contracts\Service\Attribute\Required;

final class InternalPdfManager
{
    /** @var AbstractPdf[] */
    public array $pdfs;

    #[Required]
    public PdfTemplateFetcher $pdfTemplateFetcher;

    public function getCurrentPdfService(string $slug, ?string $forcedTemplateSlug = null): ?AbstractPdf
    {
        $services = $this->getAllPdfServicesFor($slug);
        $template = $forcedTemplateSlug ?? ($this->pdfTemplateFetcher)($slug);

        foreach ($services as $service) {
            if ($service->supportTemplate($template)) {
                return $service;
            }
        }

        return null;
    }

    /** @return AbstractPdf[] */
    public function getAllPdfServicesFor(string $slug): array
    {
        return \array_filter(
            $this->pdfs,
            static function (AbstractPdf $pdf) use ($slug) {
                return $pdf->getPdfSlug() === $slug;
            }
        );
    }
}
