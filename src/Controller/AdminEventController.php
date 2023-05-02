<?php

namespace App\Controller;

use App\Model\EventManager;

class AdminEventController extends AbstractController
{
    public const MAX_LENGTH = 255;

    public function add(): string
    {
        $errors = $event = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event = array_map('trim', $_POST);

            $errors = $this->validate($event);

            if (empty($errors)) {
                $eventManager = new EventManager();
                $id = $eventManager->insert($event);

                header('Location:/Admin/Event/show?id=' . $id);
                return '';
            }
        }

        return $this->twig->render('Admin/Event/add.html.twig', ['errors' => $errors, 'event' => $event]);
    }
    private function validate(array $event): array
    {
        $errors = [];
        if (empty($event['title'])) {
            $errors[] = "Veuillez renseigner le titre, le champ est requis.";
        }

        if (empty($event['date'])) {
            $errors[] = "Veuillez renseigner la date, le champ est requis.";
        }

        if (empty($event['place'])) {
            $errors[] = "Veuillez renseigner le lieu, le champ est requis.";
        }

        if (empty($event['description'])) {
            $errors[] = "Veuillez renseigner la description, le champ est requis.";
        }

        if (mb_strlen(($event['title'])) > self::MAX_LENGTH) {
            $errors[] = "Le titre doit faire un maximum de " . self::MAX_LENGTH .
                " caractères (actuellement: " . mb_strlen($event['title']) . ")";
        }

        if (mb_strlen(($event['place'])) > self::MAX_LENGTH) {
            $errors[] = "L'endroit doit faire un maximum de " . self::MAX_LENGTH .
                " caractères (actuellement: " . mb_strlen($event['place']) . ")";
        }

        return $errors;
    }
}
