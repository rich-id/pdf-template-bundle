<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Exception;

class InvalidPdfServiceException extends \LogicException
{
    protected string $pdfClass;
    protected string $template;

    public function __construct(string $pdfClass, string $template)
    {
        parent::__construct(\sprintf('Pdf service %s does not support template %s', $pdfClass, $template));

        $this->pdfClass = $pdfClass;
        $this->template = $template;
    }

    public function getPdfClass(): string
    {
        return $this->pdfClass;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}
