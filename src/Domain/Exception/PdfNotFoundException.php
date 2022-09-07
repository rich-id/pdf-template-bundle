<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Exception;

class PdfNotFoundException extends \Exception
{
    protected string $slug;

    public function __construct(string $slug)
    {
        parent::__construct(\sprintf('No pdf found for the given slug %s.', $slug));

        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
