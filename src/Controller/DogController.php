<?php

namespace App\Controller;

use App\Model\DogManager;

class DogController extends AbstractController
{
    /**
     * Display home page
     */

    public function beauceron(): string
    {
        return $this->twig->render('Beauceron/beauceron.html.twig');
    }

    public function index(): string
    {
        $dogManager = new DogManager();
        $dogs = $dogManager->selectAll();
        return $this->twig->render('Relation/index.html.twig', ['dogs' => $dogs]);
    }
}
