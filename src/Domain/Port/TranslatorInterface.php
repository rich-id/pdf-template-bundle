<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Port;

interface TranslatorInterface
{
    /** @param array<string, string> $parameters */
    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string;
}
