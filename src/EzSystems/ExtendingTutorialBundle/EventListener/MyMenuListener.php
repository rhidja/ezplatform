<?php

namespace EzSystems\ExtendingTutorialBundle\EventListener;

use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use EzSystems\EzPlatformAdminUi\Menu\MainMenuBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MyMenuListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ConfigureMenuEvent::MAIN_MENU => ['onMenuConfigure', 0],
        ];
    }

    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu[MainMenuBuilder::ITEM_CONTENT]->addChild(
            'all_content_list',
            [
                'label' => 'Content List',
                'route' => 'ezsystems_extending_tutorial.all_content_list.list',
            ]
        );
    }
}