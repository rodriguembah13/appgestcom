<?php

namespace App\EventSubscriber;

use App\Controller\BaseController;
use App\Entity\Audit;
use App\Entity\AuditConnexion;
use App\Entity\User;
use App\Repository\AuditConnexionRepository;
use App\Repository\YearSchoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class AuditSubscriber
{
    /* private $auditConnexionRepository;
     private $entityManager;
     private $yearRepository;
     private $securityContext;
     private $tokenStorage;

     public function __construct(TokenStorageInterface $tokenStorage, YearSchoolRepository $yearSchoolRepository, EntityManagerInterface $entityManager, AuditConnexionRepository $auditConnexionRepository)
     {
         $this->auditConnexionRepository = $auditConnexionRepository;
         $this->entityManager = $entityManager;
         $this->yearRepository = $yearSchoolRepository;
         $this->tokenStorage = $tokenStorage;
     }

     public function onFosUserSecurityImplicitLogin(InteractiveLoginEvent $event)
     {
         $connexion = new AuditConnexion();
         $connexion->setCreatedAt(new \DateTime('now'));
         $connexion->setUser($event->getAuthenticationToken()->getUser());
         $connexion->setOnline(true);
         $connexion->setYear($this->yearRepository->findOneByActif());
         $this->entityManager->persist($connexion);
         $this->entityManager->flush();
         $this->securityContext = $event->getAuthenticationToken()->getUser();
     }

     public function onAuthentificateLogin(AuthenticationEvent $events)
     {
     }

     public function onControllerEvent(ControllerEvent $event)
     {
         $controller = $event->getController();
         if (is_array($controller)) {
             $controller = $controller[0];
         }
         if ($controller instanceof BaseController && $event->isMasterRequest()) {
             $audit = new Audit();
             if ($this->tokenStorage->getToken()->getUser() instanceof User) {
                 $audit->setUser($this->tokenStorage->getToken()->getUser());
                 $audit->setCreatedAt(new \DateTime('now'));
                 $audit->setController($event->getRequest()->get('_controller'));
                 $audit->setOperation($event->getRequest()->getMethod());
                 $audit->setMachine(get_current_user());
                 $audit->setYear($this->yearRepository->findOneByActif());
                 $audit->setAction($event->getRequest()->getRequestUri());
                 $this->entityManager->persist($audit);
                 $this->entityManager->flush();
             }
         }
     }

     public static function getSubscribedEvents()
     {
         return [
             'fos_user.security.implicit_login' => 'onFosUserSecurityImplicitLogin',
                 SecurityEvents::INTERACTIVE_LOGIN => 'onFosUserSecurityImplicitLogin',
                  AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthentificateLogin',
             KernelEvents::CONTROLLER => 'onControllerEvent',
         ];
     }*/
}
