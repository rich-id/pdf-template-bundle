<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Port;

interface ConfigurationInterface
{
    public function getChromeBinary(): string;

    /** @return string[] */
    public function getChromeCustomFlags(): array;
}
