<?php

namespace App\Controller;

use App\Event\EntrepotListEvent;
use KevinPapst\AdminLTEBundle\Controller\EmitterController;
use KevinPapst\AdminLTEBundle\Helper\ContextHelper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class EntrepotEmitterController extends EmitterController
{
    /**
     * @var int|null
     */
    private $maxEntrepots;

    public function __construct(EventDispatcherInterface $dispatcher, ContextHelper $helper)
    {
        parent::__construct($dispatcher);
        $this->maxEntrepots = $helper->getOption('max_navbar_messages');
    }
    /**
     * @param int|null $max
     * @return Response
     */
    public function entrepotsAction($max = null): Response
    {
        if (!$this->hasListener(EntrepotListEvent::class)) {
            return new Response();
        }

        if (null === $max) {
            $max = (int) $this->maxEntrepots;
        }

        /** @var EntrepotListEvent $listEvent */
        $listEvent = $this->dispatch(new EntrepotListEvent($max));

        return $this->render(
            'entrepot/menu/nav.html.twig',
            [
                'entrepots' => $listEvent->getEntrepots(),
                'total' => $listEvent->getTotal(),
            ]
        );
    }
}
