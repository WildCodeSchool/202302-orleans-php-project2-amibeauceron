<?php

namespace App\Controller;

use App\Model\MemberManager;

class MemberController extends AbstractController
{

    public function index(): string
    {
        $memberManager = new MemberManager();
        $members = $memberManager->selectAll('lastname');
        return $this->twig->render('Member/index.html.twig', ['members' => $members]);
    }
}
