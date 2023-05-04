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
        $search = array_map('trim', $_GET);
        $dogManager = new DogManager();
        $dogs = $dogManager->findDogs($search);
        return $this->twig->render('Relation/index.html.twig', [
            'dogs' => $dogs,
            'search' => $search,
        ]);
    }
}
