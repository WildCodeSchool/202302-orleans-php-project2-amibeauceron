<?php

namespace App\Controller;

class SoinController extends AbstractController
{
    public function index(): string
    {
        return $this->twig->render('Soin/index.html.twig');
    }
}
