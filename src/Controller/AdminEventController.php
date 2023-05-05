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
    private function validateUpload(array $files): array
    {
        $errors = [];
        $limitFileSize = '1000000';
        $authorizedMimes = ['image/jpeg', 'image/png', 'image/webp'];

        if ($files['image']['error'] !== 0) {
            $errors[] = 'Problème avec l\'upload, veuillez réessayer';
        }

        if ($files['image']['size'] > $limitFileSize) {
            $errors[] = 'Le fichier doit faire moins de ' . $limitFileSize / 1000000 . 'Mo';
        }

        if (!in_array(mime_content_type($files['image']['tmp_name']), $authorizedMimes)) {
            $errors[] = 'Le type de fichier est incorrect. Types autorisés : ' . implode(', ', $authorizedMimes);
        }

        return $errors;
    }

    private function generateImageName(array $files)
    {
        $extension = pathinfo($files['name'], PATHINFO_EXTENSION);
        $baseFilename = pathinfo($files['name'], PATHINFO_FILENAME);
        return uniqid($baseFilename, more_entropy: true) . '.' . $extension;
    }

    public function update(int $id): string
    {
        $eventManager = new EventManager();
        $event = $eventManager->selectOneById($id);
        $lastImage = $event['image'];

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event = array_map('trim', $_POST);
            $dataErrors = $this->validate($event);
            $uploadErrors = $this->validateUpload($_FILES);

            $errors = array_merge($dataErrors, $uploadErrors);

            if (empty($errors)) {
                $eventManager = new EventManager();
                $event['image'] = $lastImage;

                if (!empty($_FILES['image']['tmp_name'])) {
                    $this->deleteFile($lastImage);

                    $imageName = $this->generateImageName($_FILES['image']);
                    $event['image'] = $imageName;
                    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public/uploads/'  . $imageName);
                }

                $eventManager->update($event);

                header('Location: /administration/evenements');
            }
        }

        return $this->twig->render('Admin/Event/update.html.twig', [
            'event' => $event,
            'errors' => $errors,
        ]);
    }

    private function deleteFile(?string $imageName)
    {
        if (!empty($imageName) && file_exists(__DIR__ . '/../../public/uploads/' . $imageName)) {
            unlink(__DIR__ . '/../../public/uploads/' . $imageName);
        }
    }
}
