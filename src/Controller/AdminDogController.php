<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Model\DogManager;

class AdminDogController extends AbstractAdminController
{
    public const MAX_LENGTH_OWNER = 45;
    public const MAX_LENGTH_OWNER_CITY = 45;
    public const MAX_LENGTH_OWNER_EMAIL = 45;
    public const MAX_LENGTH_DOG_NAME = 45;
    public const LENGTH_DOG_NUMBER = 15;

    /**
     * Display dogs home page
     */
    public function index(): string
    {
        $dogManager = new DogManager();
        $dogs = $dogManager->selectAll('name');
        return $this->twig->render('Admin/Relation/index.html.twig', ['dogs' => $dogs]);
    }

    /**
     * Insert Dog
     */
    public function add(): string
    {
        $dog = $errors = $dataExistErrors = $dataFormatErrors = $dataLengthErrors = $uploadErrors = [];
        $imageName = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $dog = array_map('trim', $_POST);

            // Validations
            $dataExistErrors = $this->validateDataExist($dog);
            $dataLengthErrors = $this->validateDataLength($dog);
            $dataFormatErrors = $this->validateDataFormat($dog);

            // Validation upload
            $uploadErrors = $this->validateUpload($_FILES);

            // Merge des tableaux d'erreurs sous un seul array
            $errors = array_merge($dataExistErrors, $dataLengthErrors, $dataFormatErrors, $uploadErrors);

            // if validation is ok, insert and redirection
            if (empty($errors)) {
                if (!empty($_FILES['image']['name'])) {
                    // generate unique file name for the uplaod
                    $imageName = $this->generateImageName($_FILES['image']);
                }
                // add generated file name to current dog
                $dog['image'] = $imageName;

                // insertion
                $dogManager = new DogManager();
                $dogManager->insert($dog);

                // move upload if file not empty
                if (!empty($_FILES['image']['tmp_name'])) {
                    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public/uploads/' . $imageName);
                }
                // redirection
                header('Location:/administration/beauceron/nos-chiens');
                exit();
            }
        }

        return $this->twig->render(
            'Admin/Relation/add.html.twig',
            ['dog' => $dog, 'errors' => $errors]
        );
    }
    private function validateDataFormat(array $dog): array
    {
        $errors = [];
        $genders = ['F', 'M'];

        if (
            !filter_var($dog['owner_email'], FILTER_VALIDATE_EMAIL) ||
            !filter_var($dog['owner_email'], FILTER_SANITIZE_EMAIL)
        ) {
            $errors[] = "Le format du mail est incorrect";
        }

        if (!in_array($dog['gender'], $genders)) {
            $errors[] = "Le genre " . $dog['gender'] . " est incorrect, il doit correspondre " .
                "à l'une des valeurs suivantes : " . implode(', ', $genders);
        }
        if (!is_numeric($dog['identity_number'])) {
            $errors[] = "Le numéro d'identification du chien doit être une valeur numérique";
        }

        return $errors;
    }
    private function validateDataLength(array $dog): array
    {
        $errors = [];

        if (mb_strlen($dog['owner']) > self::MAX_LENGTH_OWNER) {
            $errors[] = "Le nom du propriétaire doit faire un maximum de " . self::MAX_LENGTH_OWNER .
                " caractères (actuellement: " . mb_strlen($dog['owner']) . ")";
        }

        if (mb_strlen($dog['owner_city']) > self::MAX_LENGTH_OWNER_CITY) {
            $errors[] = "La ville du propriétaire doit faire un maximum de " . self::MAX_LENGTH_OWNER_CITY .
                " caractères (actuellement: " . mb_strlen($dog['owner_city']) . ")";
        }

        if (mb_strlen($dog['name']) > self::MAX_LENGTH_DOG_NAME) {
            $errors[] = "Le nom du chien doit faire un maximum de " . self::MAX_LENGTH_DOG_NAME .
                " caractères (actuellement: " . mb_strlen($dog['name']) . ")";
        }

        if (mb_strlen($dog['owner_email']) > self::MAX_LENGTH_OWNER_EMAIL) {
            $errors[] = "Le mail doit faire un maximum de " . self::MAX_LENGTH_OWNER_EMAIL .
                " caractères (actuellement: " . mb_strlen($dog['owner_email']) . ")";
        }

        // Numero icad = 15 chiffres qui identifie votre animal
        if (mb_strlen($dog['identity_number']) != self::LENGTH_DOG_NUMBER) {
            $errors[] = "Le numéro d'identification du chien doit faire " . self::LENGTH_DOG_NUMBER .
                " caractères (actuellement: " . mb_strlen($dog['identity_number']) . ")";
        }
        return $errors;
    }

    private function validateDataExist(array $dog): array
    {
        $errors = [];

        if (empty($dog['owner'])) {
            $errors[] = "Veuillez renseigner le nom du propriétaire, zone obligatoire.";
        }

        if (empty($dog['owner_email'])) {
            $errors[] = "Veuillez renseigner votre mail, zone obligatoire.";
        }

        if (empty($dog['name'])) {
            $errors[] = "Veuillez renseigner le nom de votre chien, zone obligatoire.";
        }

        if (empty($dog['gender'])) {
            $errors[] = "Veuillez renseigner le genre de votre chien, zone obligatoire.";
        }

        if (!isset($dog['is_lof'])) {
            $errors[] = "Veuillez renseigner si votre chien est LOF, zone obligatoire.";
        }

        if (empty($dog['identity_number'])) {
            $errors[] = "Veuillez renseigner l'identifiant I-CAD de votre chien, zone obligatoire.";
        }

        if (empty($dog['description'])) {
            $errors[] = "Veuillez renseigner une description pour votre chien, zone obligatoire.";
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

    // delete file (on delete and l'update)
    private function deleteFile(?string $fileName)
    {
        if (!empty($fileName) && file_exists(__DIR__ . '/../../public/uploads/' . $fileName)) {
            unlink(__DIR__ . '/../../public/uploads/' . $fileName);
        }
    }

    public function delete(int $id): void
    {
        // Check Post Resquest
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // delete en bdd
            $dogManager = new DogManager();
            $dog = $dogManager->selectOneById($id);

            // supprimer un fichier existant
            $this->deleteFile($dog['image']);
            // delete row
            $dogManager->delete($id);

            // redirec admin/pneus
            header('Location:/administration/nos-chiens');
        }
    }
}
