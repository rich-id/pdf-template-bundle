<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use RichId\PdfTemplateBundle\Infrastructure\Repository\PdfTemplateConfigurationRepository;

#[ORM\Entity(repositoryClass: PdfTemplateConfigurationRepository::class)]
#[ORM\Table(name: 'module_pdf_template_configuration')]
class PdfTemplateConfiguration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'slug', type: 'string', unique: true)]
    private string $slug;

    #[ORM\Column(name: 'value', type: 'string')]
    private string $value;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
