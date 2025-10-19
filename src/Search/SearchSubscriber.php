<?php

declare(strict_types=1);

namespace Manuxi\SuluSharedToolsBundle\Search;

use Manuxi\SuluSharedToolsBundle\Search\Event\PreUpdatedEvent;
use Manuxi\SuluSharedToolsBundle\Search\Event\RemovedEvent;
use Manuxi\SuluSharedToolsBundle\Search\Event\PersistedEvent;
use Manuxi\SuluSharedToolsBundle\Search\Event\UpdatedEvent;
use Massive\Bundle\SearchBundle\Search\SearchManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SearchSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SearchManagerInterface $searchManager,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PreUpdatedEvent::class => 'onPreUpdated',
            UpdatedEvent::class => 'onUpdated',
            PersistedEvent::class => 'onPersisted',
            RemovedEvent::class => 'onRemoved',
        ];
    }

    public function onPreUpdated(PreUpdatedEvent $event): void
    {
        $this->searchManager->deindex($event->getEntity());
    }

    public function onUpdated(UpdatedEvent $event): void
    {
        $this->searchManager->index($event->getEntity());
    }

    public function onPersisted(PersistedEvent $event): void
    {
        $this->searchManager->index($event->getEntity());

    }

    public function onRemoved(RemovedEvent $event): void
    {
        $this->searchManager->deindex($event->getEntity());
    }
}
