<?php

/*
 * This file is part of the AdminLTE-Bundle demo.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class KnpMenuBuilderSubscriber configures the main navigation utilizing the KnpMenuBundle.
 */
class KnpMenuBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $security;

    public function __construct(AuthorizationCheckerInterface $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KnpMenuEvent::class => ['onSetupNavbar', 100],
        ];
    }

    /**
     * Generate the main menu.
     */
    public function onSetupNavbar(KnpMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild(
            'menu-label',
            ['label' => 'Main Navigation', 'childOptions' => $event->getChildOptions()]
        )->setAttribute('class', 'header');

        $menu->addChild(
            'homepage',
            ['route' => 'homepage', 'label' => 'menu.homepage', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fas fa-tachometer-alt');
        $menu->addChild(
            'menu-caisse',
            ['label' => 'menu.caisse.titre', 'childOptions' => $event->getChildOptions()]
        )->setAttribute('class', 'header');
        $menu->addChild(
            'caisse',
            ['route' => 'order_caisse', 'label' => 'menu.caisse.titre', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        $menu->addChild(
            'client',
            ['route' => 'customer_index', 'label' => 'menu.caisse.customer', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        $menu->addChild(
            'fournisseur',
            ['route' => 'fournisseur_index', 'label' => 'menu.caisse.fournisseur', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        $menu->addChild(
            'menu-product',
            ['label' => 'menu.product.titre', 'childOptions' => $event->getChildOptions()]
        )->setAttribute('class', 'header');

        $menu->addChild(
            'Product-category',
            ['route' => 'product_category_index', 'label' => 'menu.product.category', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        $menu->addChild(
            'Product',
            ['route' => 'product_index', 'label' => 'menu.product.titre', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        $menu->addChild(
            'entrepot',
            ['route' => 'entrepot_index', 'label' => 'menu.product.entrepot', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        $menu->addChild(
            'menu-order',
            ['label' => 'menu.order.titre', 'childOptions' => $event->getChildOptions()]
        )->setAttribute('class', 'header');
        $menu->addChild(
            'order',
            ['route' => 'order_index', 'label' => 'menu.order.order_customer', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        $menu->addChild(
            'order-fournisseur',
            ['route' => 'order_fournisseur_index', 'label' => 'menu.order.order_fournisseur', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        $menu->addChild(
            'menu-mouvement',
            ['label' => 'menu.stock.mouvement', 'childOptions' => $event->getChildOptions()]
        )->setAttribute('class', 'header');
        $menu->addChild(
            'mouvement_in',
            ['route' => 'mouvement_index', 'label' => 'menu.stock.entree_stock', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        $menu->addChild(
            'mouvement_out',
            ['route' => 'mouvement_index_out', 'label' => 'menu.stock.sortie_stock', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        $menu->addChild(
            'menu-stock',
            ['label' => 'Stock', 'childOptions' => $event->getChildOptions()]
        )->setAttribute('class', 'header');
        $menu->addChild(
            'stock',
            ['route' => 'stock_index', 'label' => 'Stock', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');

        /*     $menu->addChild(
                 'mouvement',
                 ['route' => 'mouvement_index', 'label' => 'Mouvement', 'childOptions' => $event->getChildOptions()]
             )->setLabelAttribute('icon', 'fa fa-users');*/
        $menu->addChild(
            'inventory',
            ['route' => 'inventory_index', 'label' => 'Inventory', 'childOptions' => $event->getChildOptions()]
        )->setLabelAttribute('icon', 'fa fa-users');
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $menu->addChild(
                'menu-admin',
                ['label' => 'Administration', 'childOptions' => $event->getChildOptions()]
            )->setAttribute('class', 'header');
            if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                $menu->addChild(
                    'employee',
                    ['route' => 'employee_index', 'label' => 'Employee', 'childOptions' => $event->getChildOptions()]
                )->setLabelAttribute('icon', 'fa fa-user-cog');
                $menu->addChild(
                    'permissions',
                    ['route' => 'role_permission_index', 'label' => 'permissions', 'childOptions' => $event->getChildOptions()]
                )->setLabelAttribute('icon', 'fab fa-wpforms');
            }
        }
        if ($this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild(
                'logout',
                ['route' => 'fos_user_security_logout', 'label' => 'menu.logout', 'childOptions' => $event->getChildOptions()]
            )->setLabelAttribute('icon', 'fas fa-sign-out-alt');
        } else {
            $menu->addChild(
                'login',
                ['route' => 'fos_user_security_login', 'label' => 'menu.login', 'childOptions' => $event->getChildOptions()]
            )->setLabelAttribute('icon', 'fas fa-sign-in-alt');
        }
    }
}
