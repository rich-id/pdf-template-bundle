<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Model;

interface PdfForcedTemplateSlugModelInterface extends PdfModelInterface
{
    public function getForcedTemplateSlug(): ?string;

    public function setForcedTemplateSlug(?string $forcedTemplateSlug): void;
}
