<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Pdf\Trait;

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Page;
use RichId\PdfTemplateBundle\Domain\Port\ConfigurationInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait PdfGeneratorTrait
{
    #[Required]
    public ConfigurationInterface $configuration;

    /** @return array<string, mixed> */
    abstract protected function getPdfOptions(): array;
    abstract protected function updatePage(Page $page): void;

    private function internalGeneratePdf(string $content): string
    {
        $browserFactory = new BrowserFactory($this->configuration->getChromeBinary());
        $browser = $browserFactory->createBrowser(['customFlags' => $this->configuration->getChromeCustomFlags()]);

        try {
            $page = $browser->createPage();
            $page->setHtml($content);
            $this->updatePage($page);
            $pdf = \base64_decode($page->pdf($this->getPdfOptions())->getBase64());

            $browser->close();

            return $pdf;
        } finally {
            $browser->close();
        }
    }
}
