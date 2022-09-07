<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Port;

interface TemplatingInterface
{
    /** @param array<string, string> $context */
    public function render(string $name, array $context = []): string;
}
