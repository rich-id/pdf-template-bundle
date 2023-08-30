<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Infrastructure\Adapter;

use RichId\PdfTemplateBundle\Domain\Port\TemplatingInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Twig\Environment;

class TemplatingAdapter implements TemplatingInterface
{
    #[Required]
    public Environment $twig;

    /** @param array<string, mixed> $context */
    public function render(string $name, array $context = []): string
    {
        return $this->twig->render($name, $context);
    }
}
