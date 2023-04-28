<?php

namespace App\Controller;

class CareController extends AbstractController
{
    public function index(): string
    {
        return $this->twig->render('Care/index.html.twig');
    }
}
