<?php

namespace App\Controller;

class DogController extends AbstractController
{
    /**
     * Display home page
     */

    public function beauceron(): string
    {
        return $this->twig->render('Beauceron/beauceron.html.twig');
    }
}
