<?php

namespace App\Controller;

abstract class AbstractAdminController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();

        //check user id admin and exist
        if (empty($_SESSION['user_id'])) {
            echo 'Unauthorized access';
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
    }
}
