<?php

namespace App\Controller;

use App\Model\ActualityManager;

class AdminActualityController extends AbstractController
{
    public function add(): string
    {
        $actuality = $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $actuality = array_map('trim', $_POST);

            // Validations
            if (!empty($actuality)) {
                $errors = $this->validateDatas($actuality);

                // if validation is ok, insert and redirection
                if (empty($errors)) {
                    $actualityManager = new ActualityManager();
                    $id = $actualityManager->insert($actuality);
                    header('Location:/administration/actualites/afficher?id=' . $id);
                    exit();
                }
            }
        }
        return $this->twig->render('Admin/Actuality/add.html.twig', ['actuality' => $actuality, 'errors' => $errors]);
    }

    private function validateDatas(array $actuality): array
    {
        $errors = [];

        if (empty($actuality['title'])) {
            $errors[] = 'Veuillez renseigner la zone Titre.';
        }

        if (strlen($actuality['title']) > 100) {
            $errors[] = sprintf(
                'la zone Titre ne doit pas dépasser 100 caractères (actuellement : {%s}).',
                strlen($actuality['title'])
            );
        }

        if (empty($actuality['content'])) {
            $errors[] = 'Veuillez renseigner la zone Texte.';
        }
        return $errors;
    }
}
