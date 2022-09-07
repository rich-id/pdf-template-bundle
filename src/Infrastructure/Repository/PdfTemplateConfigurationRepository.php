<?php

declare(strict_types=1);

namespace RichId\PdfTemplateBundle\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RichId\PdfTemplateBundle\Domain\Entity\PdfTemplateConfiguration;

/**
 * @extends ServiceEntityRepository<PdfTemplateConfiguration>
 *
 * @method PdfTemplateConfiguration findOneBySlug(string $slug)
 */
class PdfTemplateConfigurationRepository extends ServiceEntityRepository
{
    /** @codeCoverageIgnore  */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PdfTemplateConfiguration::class);
    }
}
