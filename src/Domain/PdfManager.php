<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain;

use RichId\PdfTemplateBundle\Domain\Exception\PdfNotFoundException;
use RichId\PdfTemplateBundle\Domain\Internal\InternalPdfManager;
use RichId\PdfTemplateBundle\Domain\Model\PdfForcedTemplateSlugModelInterface;
use RichId\PdfTemplateBundle\Domain\Model\PdfModelInterface;
use Symfony\Contracts\Service\Attribute\Required;

final class PdfManager
{
    #[Required]
    public InternalPdfManager $internalPdfanager;

    public function generatePdf(string $slug, ?PdfModelInterface $data = null, ?string $forcedTemplateSlug = null): string
    {
        if ($data instanceof PdfForcedTemplateSlugModelInterface) {
            $data->setForcedTemplateSlug($forcedTemplateSlug);
        }

        $service = $this->internalPdfanager->getCurrentPdfService($slug, $forcedTemplateSlug);

        if ($service === null) {
            throw new PdfNotFoundException($slug);
        }

        $method = new \ReflectionMethod($service, 'getPdf');
        $method->setAccessible(true);

        $pdf = $method->invoke($service->setData($data));
        $method->setAccessible(false);

        return $pdf;
    }
}
