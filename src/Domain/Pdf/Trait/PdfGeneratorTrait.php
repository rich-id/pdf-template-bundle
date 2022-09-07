<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Pdf\Trait;

use HeadlessChromium\BrowserFactory;
use RichId\PdfTemplateBundle\Domain\Port\ConfigurationInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait PdfGeneratorTrait
{
    #[Required]
    public ConfigurationInterface $configuration;

    private function internalGeneratePdf(string $content): string
    {
        $browserFactory = new BrowserFactory($this->configuration->getChromeBinary());
        $browser = $browserFactory->createBrowser(['customFlags' => $this->configuration->getChromeCustomFlags()]);

        try {
            $page = $browser->createPage();
            $page->setHtml($content);
            $page->waitUntilContainsElement('#pdf-fonts-loaded'); // todo not here
            $pdf = \base64_decode($page->pdf(['printBackground' => true, 'landscape' => true, 'preferCSSPageSize' => true])->getBase64()); // todo options not here

            $browser->close();

            return $pdf;
        } finally {
            $browser->close();
        }
    }
}
