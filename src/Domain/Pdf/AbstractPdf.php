<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Pdf;

use HeadlessChromium\Page;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use RichId\PdfTemplateBundle\Domain\Constant;
use RichId\PdfTemplateBundle\Domain\Exception\MissingFilesystemException;
use RichId\PdfTemplateBundle\Domain\Exception\PdfNotFoundException;
use RichId\PdfTemplateBundle\Domain\Exception\PdfSkippedException;
use RichId\PdfTemplateBundle\Domain\Fetcher\PdfTemplateFetcher;
use RichId\PdfTemplateBundle\Domain\Internal\InternalPdfManager;
use RichId\PdfTemplateBundle\Domain\Model\SaveablePdfModel;
use RichId\PdfTemplateBundle\Domain\Pdf\Trait\PdfDataTrait;
use RichId\PdfTemplateBundle\Domain\Pdf\Trait\PdfGeneratorTrait;
use RichId\PdfTemplateBundle\Domain\Pdf\Trait\PdfMergerTrait;
use RichId\PdfTemplateBundle\Domain\Pdf\Trait\PdfProtectionTrait;
use RichId\PdfTemplateBundle\Domain\Port\ConfigurationInterface;
use RichId\PdfTemplateBundle\Domain\Port\TemplatingInterface;
use RichId\PdfTemplateBundle\Domain\Port\TranslatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractPdf
{
    use PdfDataTrait;
    use PdfGeneratorTrait;
    use PdfMergerTrait;
    use PdfProtectionTrait;

    public const TEMPLATES = [Constant::DEFAULT_TEMPLATE];

    protected const TRANSLATION_DOMAIN = 'pdf';
    protected const TEMPLATING_FOLDER = 'pdf';
    protected const MODIFICATION_ALLOWED = true;
    protected const OTHER_PAGES = [];

    #[Required]
    public TemplatingInterface $templating;

    #[Required]
    public TranslatorInterface $translator;

    #[Required]
    public PdfTemplateFetcher $pdfTemplateFetcher;

    #[Required]
    public ConfigurationInterface $configuration;

    #[Required]
    public InternalPdfManager $internalPdfanager;

    #[Required]
    public FilesystemMap $filesystemMap;  /* @phpstan-ignore-line */

    abstract public function getPdfSlug(): string;

    protected function assertValidParameters(): void
    {
    }

    protected function updatePage(Page $page): void
    {
    }

    /** @return array<string, mixed> */
    protected function getPdfOptions(): array
    {
        return ['printBackground' => true];
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

    /** @return array<string, callable> */
    protected function otherPagesDatas(): array
    {
        return [];
    }

    protected function skippedIf(): bool
    {
        return false;
    }

    final protected function generatePdf(): string
    {
        $pdf = $this->internalGeneratePdf($this->getContent());
        $othersPages = $this->generateOtherPages();

        if (!empty($othersPages)) {
            $pdf = $this->mergePdfs(\array_merge([$pdf], $othersPages));
        }

        if (static::MODIFICATION_ALLOWED) {
            return $pdf;
        }

        return $this->internalProtectPdf($pdf);
    }

    /** @return string[] */
    final protected function generateOtherPages(): array
    {
        $othersPages = [];

        foreach (static::OTHER_PAGES as $otherPageSlug) {
            $pageService = $this->internalPdfanager->getCurrentPdfService($otherPageSlug);

            if ($pageService === null) {
                throw new PdfNotFoundException($otherPageSlug);
            }

            $data = $this->data;
            $customDataFn = $this->otherPagesDatas()[$otherPageSlug] ?? null;

            if (\is_callable($customDataFn)) {
                $data = $customDataFn();
            }
            $pageService->setData($data);
            $pageService->assertValidParameters();

            if ($pageService->skippedIf()) {
                continue;
            }

            $othersPages[] = $pageService->generatePdf();
        }

        return $othersPages;
    }

    final protected function getPdf(): string
    {
        $this->assertValidParameters();

        $seveableModel = $this->getSaveableModel();

        if ($seveableModel !== null && !$this->filesystemMap->has($seveableModel->getFilesystemName())) {
            throw new MissingFilesystemException($seveableModel->getFilesystemName());
        }

        $fs = $seveableModel !== null ? $this->filesystemMap->get($seveableModel->getFilesystemName()) : null;

        if ($seveableModel !== null && $fs !== null && $fs->has($seveableModel->getFileName()) && !$seveableModel->canForceNewGeneration()) {
            return $fs->get($seveableModel->getFileName())->getContent();
        }

        if ($this->skippedIf()) {
            throw new PdfSkippedException();
        }

        $pdf = $this->generatePdf();

        if ($seveableModel !== null && $fs !== null && $seveableModel->canSave()) {
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
