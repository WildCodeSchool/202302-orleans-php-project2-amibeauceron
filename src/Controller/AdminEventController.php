<?php

namespace App\Controller;

use App\Model\EventManager;

class AdminEventController extends AbstractAdminController
{
    public const MAX_LENGTH = 255;

    public function index(): string
    {
        $eventManager = new EventManager();
        $events = $eventManager->selectAll('title');
        return $this->twig->render('Admin/Event/index.html.twig', ['events' => $events]);
    }

    public function add(): string
    {
        $errors = $event = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event = array_map('trim', $_POST);

            $validateErrors = $this->validate($event);
            $uploadErrors = [];

            if (!empty($_FILES)) {
                $uploadErrors = $this->validateUpload($_FILES);
            }

            $errors = array_merge($uploadErrors, $validateErrors);

            if (empty($errors)) {
                $eventManager = new EventManager();
            }
            if (!empty($_FILES['image']['tmp_name'])) {
                $imageName = $this->generateImageName($_FILES['image']);
                $event['image'] = $imageName;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public/uploads/'  . $imageName);
            }
            if (empty($errors)) {
                $eventManager = new EventManager();
                $eventManager->insert($event);

                header('Location: /administration/evenements');
                return '';
            }
        }

        return $this->twig->render('Admin/Event/add.html.twig', ['errors' => $errors, 'event' => $event]);
    }
    private function validate(array $event): array
    {
        $errors = [];
        if (empty($event['title'])) {
            $errors[] = "Veuillez renseigner le titre.";
        }

        if (empty($event['date'])) {
            $errors[] = "Veuillez renseigner la date.";
        }

        if (empty($event['place'])) {
            $errors[] = "Veuillez renseigner le lieu.";
        }

        if (empty($event['description'])) {
            $errors[] = "Veuillez renseigner la description.";
        }

        if (mb_strlen(($event['title'])) > self::MAX_LENGTH) {
            $errors[] = "Le titre doit faire un maximum de " . self::MAX_LENGTH .
                " caractères (actuellement: " . mb_strlen($event['title']) . ")";
        }

        if (mb_strlen(($event['place'])) > self::MAX_LENGTH) {
            $errors[] = "Le lieu doit faire un maximum de " . self::MAX_LENGTH .
                " caractères (actuellement: " . mb_strlen($event['place']) . ")";
        }

        return $errors;
    }

    public function delete(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST)) {
                $id = $_POST['id'];
                $eventManager = new EventManager();
                $event = $eventManager->selectOneById($id);

                $this->deleteFile($event['image']);

                $eventManager->delete($id);
            }
        }
        header('Location: /administration/evenements');
        return '';
    }

    private function validateUpload(array $image): array
    {
        $errors = [];
        $limitFileSize = '1000000';
        $uploadCodesErrors = [
            0 => 'Il n\'y a pas d\'erreur, le fichier a été téléchargé avec succès',
            1 => 'Le fichier téléchargé dépasse la directive upload_max_filesize dans php.ini',
            2 => 'Le fichier téléchargé dépasse la directive MAX_FILE_SIZE spécifiée dans le formulaire HTML',
            3 => 'Le fichier téléchargé n\'a été que partiellement téléchargé',
            4 => 'Aucun fichier n\'a été téléchargé',
            6 => 'Il manque un dossier temporaire',
            7 => 'Impossible d\'écrire le fichier sur le disque.',
            8 => 'Une extension PHP a arrêté le téléchargement du fichier.',
        ];

        if (!empty($image['image']['name']) && $image['image']['error'] !== 0) {
            $errors[] = "Problème avec l\'upload, veuillez réessayer." .
                "Erreur code :{$image['image']['error']}" . " Message :{$uploadCodesErrors[$image['image']['error']]}.";
        } elseif (is_uploaded_file($image['image']['tmp_name'])) {
            if ($image['image']['size'] > $limitFileSize) {
                $errors[] = 'Le fichier doit faire moins de ' . $limitFileSize / 1000000 . 'Mo';
            }
            $authorizedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array(mime_content_type($image['image']['tmp_name']), $authorizedMimes)) {
                $errors[] = 'Le type de fichier est incorrect. Types autorisées : ' . implode(', ', $authorizedMimes);
            }
        }
        return $errors;
    }

    private function generateImageName(array $image)
    {
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $baseFilename = pathinfo($image['name'], PATHINFO_FILENAME);
        return uniqid($baseFilename, more_entropy: true) . '.' . $extension;
    }

    public function update($id): string
    {
        $eventManager = new EventManager();
        $event = $eventManager->selectOneById($id);
        $image = $event['image'];

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event = array_map('trim', $_POST);
            $dataErrors = $this->validate($event);
            $uploadErrors = $this->validateUpload($_FILES);

            $errors = array_merge($dataErrors, $uploadErrors);

            if (empty($errors)) {
                $eventManager = new EventManager();
                $event['image'] = $image;

                if (!empty($_FILES['image']['tmp_name'])) {
                    $this->deleteFile($image);

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
