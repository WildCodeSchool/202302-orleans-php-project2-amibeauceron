<?php

namespace App\Controller;

use App\Model\ActualityManager;

class AdminActualityController extends AbstractController
{
    public const MAX_LENGTH_TITLE = 100;
    public const MAX_LENGTH_CONTENT = 65535;

    public function index(): string
    {
        $actualityManager = new ActualityManager();
        $actualities = $actualityManager->selectAll('creation_date');
        return $this->twig->render('Admin/Actuality/index.html.twig', ['actualities' => $actualities]);
    }

    public function add(): string
    {
        $actuality = $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $actuality = array_map('trim', $_POST);

            // Validations
            $errors = $this->validateData($actuality);

            // if validation is ok, insert and redirection
            if (empty($errors)) {
                $actualityManager = new ActualityManager();
                $id = $actualityManager->insert($actuality);
                header('Location:/administration/actualites/afficher?id=' . $id);
                exit();
            }
        }
        return $this->twig->render('Admin/Actuality/add.html.twig', ['actuality' => $actuality, 'errors' => $errors]);
    }

    private function validateData(array $actuality): array
    {
        $errors = [];
        if (empty($actuality['title'])) {
            $errors[] = "Veuillez renseigner le Titre, zone obligatoire.";
        }

        if (empty($actuality['content'])) {
            $errors[] = "Veuillez renseigner le Texte, zone obligatoire.";
        }

        if (mb_strlen(($actuality['title'])) > self::MAX_LENGTH_TITLE) {
            $errors[] = "Le Titre doit faire un maximum de " . self::MAX_LENGTH_TITLE .
                " caractères (actuellement: " . mb_strlen($actuality['title']) . ")";
        }

        if (mb_strlen(($actuality['content'])) > self::MAX_LENGTH_CONTENT) {
            $errors[] = "Le Texte doit faire un maximum de " . self::MAX_LENGTH_CONTENT .
                " caractères (actuellement: " . mb_strlen($actuality['content']) . ")";
        }
        return $errors;
    }
}
