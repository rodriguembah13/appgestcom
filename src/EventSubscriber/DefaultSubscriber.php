<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;

class DefaultSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;
    private $sessionTokenStorage;



    public function onControllerEvent(ControllerEvent $event)
    {
      //  $this->sessionTokenStorage->setToken('1', '');
    }

    public function onKernelResponse(ResponseEvent $event)
    {
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
    }

    public static function getSubscribedEvents()
    {
        return [
            'ControllerEvent' => 'onControllerEvent',
            KernelEvents::CONTROLLER => 'onControllerEvent',
            KernelEvents::RESPONSE => 'onKernelResponse',
            KernelEvents::REQUEST => ['onKernelRequest'],
        ];
    }
}
