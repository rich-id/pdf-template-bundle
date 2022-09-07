<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Infrastructure\Adapter;

use RichId\PdfTemplateBundle\Domain\Port\ConfigurationInterface;
use RichId\PdfTemplateBundle\Infrastructure\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ConfigurationAdapter implements ConfigurationInterface
{
    #[Required]
    public ParameterBagInterface $parameterBag;

    public function getChromeBinary(): string
    {
        return Configuration::get('chrome_binary', $this->parameterBag);
    }

    public function getChromeCustomFlags(): array
    {
        return Configuration::get('chrome_custom_flags', $this->parameterBag);
    }
}
