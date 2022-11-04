<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Exception;

class PdfSkippedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Pdf is skipped.');
    }
}
