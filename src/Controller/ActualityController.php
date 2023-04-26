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

    public function show(int $id): string
    {
        $actualityManager = new ActualityManager();
        $actuality = $actualityManager->selectOneById($id);
        return $this->twig->render('Actuality/show.html.twig', ['actuality' => $actuality]);
    }
}
