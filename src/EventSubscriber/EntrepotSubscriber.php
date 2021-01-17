<?php

namespace App\EventSubscriber;

use App\Event\EntrepotListEvent;
use App\Repository\EntrepotRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class EntrepotSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    protected $security;
    protected $entrepotRepository;

    public function __construct(Security $security, EntrepotRepository $entrepotRepository)
    {
        $this->security = $security;
        $this->entrepotRepository = $entrepotRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EntrepotListEvent::class => ['onMessages', 100],
        ];
    }

    public function onMessages(EntrepotListEvent $event)
    {
        $user = $this->security->getUser();
        $comments = [];
        $entrepots = $this->entrepotRepository->findAll();
        foreach ($entrepots as $entrepot) {
            $event->addEntrepot($entrepot);
        }
        $event->setTotal(count($entrepots));
    }
}
