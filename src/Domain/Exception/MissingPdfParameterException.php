<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Exception;

class MissingPdfParameterException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Missing parameters given for this pdf.');
    }
}
