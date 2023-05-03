<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Model\DogManager;

class AdminDogController extends AbstractController
{
    /**
     * Display Admin home page
     */
    public function index(): string
    {
        $dogManager = new DogManager();
        $dogs = $dogManager->selectAll();
        return $this->twig->render('Admin/Relation/index.html.twig', ['dogs' => $dogs]);
    }
}
