<?php

declare(strict_types=1);

namespace Manuxi\SuluSharedToolsBundle\Search\Event;

use Manuxi\SuluSharedToolsBundle\Entity\Interfaces\SearchableInterface;
use Symfony\Contracts\EventDispatcher\Event as SymfonyEvent;

abstract class AbstractEvent extends SymfonyEvent
{
    public function __construct(private readonly SearchableInterface $entity)
    {
    }

    public function getEntity(): SearchableInterface
    {
        return $this->entity;
    }
}
