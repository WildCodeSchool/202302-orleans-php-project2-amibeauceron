<?php

namespace App\Controller;

use App\Model\EventManager;

class EventController extends AbstractController
{
    public function index(): string
    {
        $eventManager = new EventManager();
        $events = $eventManager->selectAll('title');
        return $this->twig->render('Event/event.html.twig', ['events' => $events]);
    }
}
