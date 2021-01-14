<?php

namespace App\EventSubscriber;

use App\Repository\ArticlesRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{

    // private $twig;
    // private $articlesRepository;

    public function __construct(Environment $twig, ArticlesRepository $articlesRepository)
    {
        $this->twig = $twig;
        $this->articlesRepository = $articlesRepository;
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        // $this->twig->addGlobal('articles', $this->articlesRepository->findAll());
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
