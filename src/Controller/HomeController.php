<?php

namespace App\Controller;

use App\Model\ActualityManager;
use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Display home page
     */
    public function index(): string
    {
        $actualityManager = new ActualityManager();
        $actualities = $actualityManager->selectLastThree();
        return $this->twig->render('Home/index.html.twig', ['actualities' => $actualities]);
    }
}
