<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Exception;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class MissingFilesystemException extends InvalidConfigurationException
{
    public function __construct(string $name)
    {
        parent::__construct(\sprintf('Missing filesystem %s.', $name));
    }
}
