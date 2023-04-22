<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Initialized some Controller common features (Twig...)
 */
abstract class AbstractController
{
    protected Environment $twig;
    protected array|false $user;

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

        // Create fictif user admin to test administration pages
        $this->user = ['Name' => 'Administrator', 'Role' => 'Admin', 'email' => 'admin.wild@gmail.com'];
        // Add global twig variable to store USER connected
        $this->twig->addGlobal('user', $this->user);
    }
}
