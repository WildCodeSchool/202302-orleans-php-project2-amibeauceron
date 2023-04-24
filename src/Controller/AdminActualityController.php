<?php

namespace App\Controller;

use App\Model\ActualityManager;

class AdminActualityController extends AbstractController
{
    public const MAX_LENGTH_TITLE = 100;
    public const MAX_LENGTH_CONTENT = 65535;

    public function edit(int $id): ?string
    {
        $errors = [];
        // On recupère l'actualité en base
        $actualityManager = new ActualityManager();
        $actuality = $actualityManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $actuality = array_map('trim', $_POST);

            // validations
            $errors = $this->validateData($actuality);

            // if validation is ok, update and redirection
            if (empty($errors)) {
                $actualityManager->update($actuality);
                header('Location: /asministration/actualites/afficher?id=' . $id);
                // we are redirecting so we don't want any content rendered
                return null;
            }
        }

        return $this->twig->render('Admin/Actuality/edit.html.twig', ['actuality' => $actuality, 'errors' => $errors]);
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
