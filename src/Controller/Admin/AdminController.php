<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * Display Admin home page
     */
    public function index(): string
    {
        return $this->twig->render('Admin/index.html.twig');
    }
}
