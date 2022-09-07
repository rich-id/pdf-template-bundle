<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Infrastructure\DependencyInjection\CompilerPass;

use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use RichId\PdfTemplateBundle\Domain\Internal\InternalPdfManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PdfsCompilerPass extends AbstractCompilerPass
{
    public const TAG = 'pdf_template.pdf';

    public function process(ContainerBuilder $container): void
    {
        $references = self::getReferencesByTag($container, self::TAG);
        $definition = $container->getDefinition(InternalPdfManager::class);
        $definition->setProperty('pdfs', $references);
    }
}
