<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Pdf;

use Knp\Bundle\GaufretteBundle\FilesystemMap;
use RichId\PdfTemplateBundle\Domain\Constant;
use RichId\PdfTemplateBundle\Domain\Exception\MissingFilesystemException;
use RichId\PdfTemplateBundle\Domain\Model\SaveablePdfModel;
use RichId\PdfTemplateBundle\Domain\Pdf\Trait\PdfDataTrait;
use RichId\PdfTemplateBundle\Domain\Fetcher\PdfTemplateFetcher;
use RichId\PdfTemplateBundle\Domain\Pdf\Trait\PdfGeneratorTrait;
use RichId\PdfTemplateBundle\Domain\Pdf\Trait\PdfProtectionTrait;
use RichId\PdfTemplateBundle\Domain\Port\ConfigurationInterface;
use RichId\PdfTemplateBundle\Domain\Port\TemplatingInterface;
use RichId\PdfTemplateBundle\Domain\Port\TranslatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractPdf
{
    use PdfDataTrait;
    use PdfGeneratorTrait;
    use PdfProtectionTrait;

    public const TEMPLATES = [Constant::DEFAULT_TEMPLATE];

    protected const TRANSLATION_DOMAIN = 'pdf';
    protected const TEMPLATING_FOLDER = 'pdf';
    protected const MODIFICATION_ALLOWED = true;

    #[Required]
    public TemplatingInterface $templating;

    #[Required]
    public TranslatorInterface $translator;

    #[Required]
    public PdfTemplateFetcher $pdfTemplateFetcher;

    #[Required]
    public ConfigurationInterface $configuration;

    #[Required]
    public FilesystemMap $filesystemMap;

    abstract public function getPdfSlug(): string;

    protected function assertValidParameters(): void
    {
    }

    protected function getSaveableModel(): ?SaveablePdfModel
    {
        return null;
    }

    public function getName(): string
    {
        return $this->translator->trans(
            \sprintf('%s.name', $this->getPdfSlug()),
            [],
            static::TRANSLATION_DOMAIN
        );
    }

    protected function getContent(): string
    {
        $data = $this->customBodyParameters();
        $data['data'] = $this->data;

        return $this->templating->render(
            \sprintf('%s/%s/%s.html.twig', static::TEMPLATING_FOLDER, $this->getPdfSlug(), $this->getTemplateSlug()),
            $data
        );
    }

    /** @return array<string, mixed> */
    protected function customBodyParameters(): array
    {
        return [];
    }

    final protected function generatePdf(): string
    {
        $pdf = $this->internalGeneratePdf($this->getContent());

        if (static::MODIFICATION_ALLOWED) {
            return $pdf;
        }

        return $this->internalProtectPdf($pdf);
    }

    final protected function getPdf(): string
    {
        $this->assertValidParameters();

        $seveableModel = $this->getSaveableModel();

        if ($seveableModel !== null && !$this->filesystemMap->has($seveableModel->getFilesystemName())) {
            throw new MissingFilesystemException($seveableModel->getFilesystemName());
        }

        $fs = $seveableModel !== null ? $this->filesystemMap->get($seveableModel->getFilesystemName()) : null;

        if ($fs !== null && $fs->has($seveableModel->getFileName()) && !$seveableModel->canForceNewGeneration()) {
            return $fs->get($seveableModel->getFileName())->getContent();
        }

        $pdf = $this->generatePdf();

        if ($fs !== null && $seveableModel->canSave()) {
            $fs->createFile($seveableModel->getFileName())->setContent($pdf);
        }

        return $pdf;
    }

    final public function supportTemplate(string $template): bool
    {
        return \in_array($template, static::TEMPLATES);
    }

    final protected function getTemplateSlug(): string
    {
        return ($this->pdfTemplateFetcher)($this->getPdfSlug());
    }
}