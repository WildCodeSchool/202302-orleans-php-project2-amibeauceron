<?php

namespace App\Controller;

use App\Model\ActualityManager;
use Exception;

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
        $actuality = $errors = $dataErrors = $uploadErrors = [];
        $imageName = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $actuality = array_map('trim', $_POST);

            // Validations
            $dataErrors = $this->validateData($actuality);

            // Validation upload
            $uploadErrors = $this->validateUpload($_FILES);

            // Merge des tableaux d'erreurs sous un seul array
            $errors = array_merge($dataErrors, $uploadErrors);

            // if validation is ok, insert and redirection
            if (empty($errors)) {
                if (!empty($_FILES['image']['name'])) {
                    // generate unique file name for the uplaod
                    $imageName = $this->generateImageName($_FILES['image']);
                }
                // add generated file name to current actuality
                $actuality['image_path'] = $imageName;

                // insertion
                $actualityManager = new ActualityManager();
                $id = $actualityManager->insert($actuality);

                // move upload if file not empty
                if (!empty($_FILES['image']['tmp_name'])) {
                    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public/uploads/' . $imageName);
                }
                // redirection
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

    private function validateUpload(array $files): array
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

        if (!empty($files['image']['name']) && $files['image']['error'] !== 0) {
            $errors[] = "Problème avec l\'upload, veuillez réessayer." .
                "Erreur code :{$files['image']['error']}" . " Message :{$uploadCodesErrors[$files['image']['error']]}.";
        } elseif (is_uploaded_file($files['image']['tmp_name'])) {
            //check size of the file
            if ($files['image']['size'] > $limitFileSize) {
                $errors[] = 'Le fichier doit faire moins de ' . $limitFileSize / 1000000 . 'Mo';
            }
            //check authorized type MiMe
            $authorizedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array(mime_content_type($files['image']['tmp_name']), $authorizedMimes)) {
                $errors[] = 'Le type de fichier est incorrect. Types autorisées : ' . implode(', ', $authorizedMimes);
            }
        }
        return $errors;
    }

    // génère un nom unique pour un fichier
    private function generateImageName(array $files)
    {
        $extension = pathinfo($files['name'], PATHINFO_EXTENSION);
        $baseFilename = pathinfo($files['name'], PATHINFO_FILENAME);
        return uniqid($baseFilename, more_entropy: true) . '.' . $extension;
    }
}
