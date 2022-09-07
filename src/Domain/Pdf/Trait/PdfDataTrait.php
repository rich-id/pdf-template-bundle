<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Pdf\Trait;

use RichId\PdfTemplateBundle\Domain\Model\PdfModelInterface;

trait PdfDataTrait
{
    protected ?PdfModelInterface $data = null;

    public function setData(?PdfModelInterface $data): self
    {
        $this->data = $data;

        return $this;
    }
}
