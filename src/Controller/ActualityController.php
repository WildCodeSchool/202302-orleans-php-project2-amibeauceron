<?php

namespace App\Controller;

use App\Model\ActualityManager;

class ActualityController extends AbstractController
{
    /**
     * List of Actualities
     */
    public function index(): string
    {
        $actualityManager = new ActualityManager();
        $actualities = $actualityManager->selectAll('creation_date');

        return $this->twig->render('Actuality/index.html.twig', ['actualities' => $actualities]);
    }
}
