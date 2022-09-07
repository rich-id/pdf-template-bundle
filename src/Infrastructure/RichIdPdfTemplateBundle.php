<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Infrastructure;

use RichCongress\BundleToolbox\Configuration\AbstractBundle;

class RichIdPdfTemplateBundle extends AbstractBundle
{
    /** @var array<string, string> */
    protected static $doctrineAttributeMapping = [
        'RichId\\PdfTemplateBundle\\Domain\\Entity' => __DIR__ . '/../Domain/Entity',
    ];
}
