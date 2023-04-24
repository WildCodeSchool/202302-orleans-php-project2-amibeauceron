<?php

namespace App\Controller;

use App\Model\ActualityManager;

class ActualityController extends AbstractController
{
    /**
     * List of Actualities
     */
    public function index(): string
    {
        $actualityManager = new ActualityManager();
        $actualities = $actualityManager->selectAll('creation_date');

        return $this->twig->render('Actuality/index.html.twig', ['actualities' => $actualities]);
    }

    public function edit(int $id): ?string
    {
        $errors = [];
        // On recupère l'actualité en base
        $actualityManager = new ActualityManager();
        $actuality = $actualityManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST)) {
                // clean $_POST data
                $actuality = array_map('trim', $_POST);

                // TODO validations (length, format...)
                $errors = $this->validateDatas($actuality);

                // if validation is ok, update and redirection
                if (empty($errors)) {
                    $actualityManager->update($actuality);
                    header('Location: /asministration/actualites/afficher?id=' . $id);
                    // we are redirecting so we don't want any content rendered
                    return null;
                }
            }
        }

        return $this->twig->render('Admin/Actuality/edit.html.twig', ['actuality' => $actuality, 'errors' => $errors]);
    }

    private function validateDatas(array $actuality): array
    {
        $errors = [];
        if (empty($actuality['title'])) {
            $errors[] = "Veuillez renseigner le Titre, zone obligatoire.";
        }

        if (empty($actuality['content'])) {
            $errors[] = "Veuillez renseigner le Texte, zone obligatoire.";
        }
        return $errors;
    }
}
