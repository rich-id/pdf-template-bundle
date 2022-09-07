<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Infrastructure\DependencyInjection\PrependConfiguration;

use RichCongress\BundleToolbox\Configuration\PrependConfiguration\AbstractDoctrineMigrationPrependConfiguration;

final class DoctrineMigrationPrependConfiguration extends AbstractDoctrineMigrationPrependConfiguration
{
    /** @return array<string, string> */
    protected function getBindings(): array
    {
        return [
            'RichId\PdfTemplateBundle\Infrastructure\Migrations' => __DIR__ . '/../../Migrations',
        ];
    }
}
