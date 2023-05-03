<?php

namespace App\Controller;

use App\Model\UserManager;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Initialized some Controller common features (Twig...)
 */
abstract class AbstractController
{
    protected Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => true,
            ]
        );
        $this->twig->addExtension(new DebugExtension());

        //add user id to global twig variable
        if (!empty($_SESSION['user_id'])) {
            $userManager = new UserManager();
            $user = $userManager->selectOneById($_SESSION['user_id']);
            $this->twig->addGlobal('user', $user);
        }
    }
}
