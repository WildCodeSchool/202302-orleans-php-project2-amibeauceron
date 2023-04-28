<?php

namespace App\Controller;

use App\Model\MembershipManager;

class MembershipController extends AbstractController
{
    public function index(): string
    {
        return $this->twig->render('Membership/index.html.twig');
    }
}
