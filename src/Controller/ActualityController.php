<?php

namespace App\Controller;

use App\Model\ActualityManager;
use Directory;

define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

class ActualityController extends AbstractController
{
    private const UPLOAD_DIRECTOTRY = 'uploads/actualities'; // dossier de destination pour les upload
    /**
     * List of Actualities
     */
    public function index(): string
    {
        $actualityManager = new ActualityManager();
        $actualities = $actualityManager->selectAll('creation_date');
        return $this->twig->render('Actuality/index.html.twig', ['actualities' => $actualities]);
    }
    public function add(): string
    {
        $actuality = $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $actuality = array_map('trim', $_POST);

            // Validations
            if (!empty($actuality)) {
                $errors = $this->validateDatas($actuality);

                //on effectue l'upload si aucunes erreurs de validation avant
                if (empty($errors)) {
                    //on recupère le fichier sur le serveur
                    $uplaodResult = $this->uploadFile();

                    // On rappatrie les erreurs d'upload dans la variable $errors pour l'affichage
                    foreach ($uplaodResult['uploadErrors'] as $uploadError) {
                        $errors[] = $uploadError;
                    }

                    // Si processus d'upload OK, on renseigne le chemin du fichier provenant du serveur.
                    if (empty($uplaodResult['uploadErrors']) && !empty($uplaodResult['uploadedFiles'])) {
                        $actuality['image_path'] = $uplaodResult['uploadedFiles'][0];
                    }
                }

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
    private function uploadFile(): array
    {
        $uploaded = []; // Store uploaded files here -> OK
        $uploadErrors = []; // Store failed upload files here -> KO
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

        // on check si au minimum un fichier est present
        if (!empty($_FILES['files']['name'][0])) {
            // on recupère le tableau de fichiers
            $files = $_FILES['files'];

            // Extensions autorisées pour l'upload du fichier image
            $extensionsAllowed = [
                'jpeg',
                'jpg',
                'png',
                'gif',
                'webp'
            ];

            //Type image
            $typeMimeAllowed = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'image/webp',
            ];

            $maxSize = 1 * MB; //2Mo Par défaut, l'upload de fichier en PHP est limité à 2Mo.

            // on parcours chaque fichier
            foreach ($files['name'] as $position => $fileName) {
                // on récupère chaque proprieté du fichier en cours de traitement
                $fileTmpName = $files['tmp_name'][$position];
                $fileSize = $files['size'][$position];
                $fileError = $files['error'][$position];
                $fileType = $files['type'][$position];

                // on recupère l'extension du fichier sans le point avant, pour le control suivant
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // on check que l'extension est acceptée
                if (in_array($fileExtension, $extensionsAllowed)) {
                    if (in_array($fileType, $typeMimeAllowed)) {
                        // on check qu'il n'y a pas de code erreur en retour
                        if ($fileError === 0) {
                            // on check la taille du fichier
                            if ($fileSize <= $maxSize) {
                                // on génère un nom unique pour l'enregistrement
                                $fileNameNew = uniqid('', true) . '_' . $fileName;
                                // chemin de stockage complet du fichier sur le serveur
                                $fileDestination = self::UPLOAD_DIRECTOTRY . $fileNameNew;
                                //si le dossier de destination pour l'upload n'existe pas on le créé
                                if (!file_exists(self::UPLOAD_DIRECTOTRY)) {
                                    mkdir(self::UPLOAD_DIRECTOTRY, 0777, true);
                                }
                                // on déplace le fichier du dossier temporaire vers le dossier d'uplaod
                                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                                    $uploaded[$position] = $fileDestination;
                                } else {
                                    $uploadErrors[$position] = "Échec du téléchargement du fichier : [{$fileName}].";
                                }
                            } else {
                                $uploadErrors[$position] = "Fichier: [{$fileName}] trop volumineux, " .
                                    " la taille maximum autorisée est de {$maxSize}MB.";
                            }
                        } else {
                            $uploadErrors[$position] = "Fichier: [{$fileName}] en erreur avec le code :{$fileError}" .
                                " Description du message :{$uploadCodesErrors[$fileError]}.";
                        }
                    } else {
                        $uploadErrors[$position] = "Fichier: [{$fileName}] avec un type MIME {$fileType} " .
                            " non autorisé.";
                    }
                } else {
                    $uploadErrors[$position] = "Fichier: [{$fileName}] avec l'extension {$fileExtension} " .
                        "non autorisée.";
                }
            }
        }
        return ['uploadErrors' => $uploadErrors, 'uploadedFiles' => $uploaded];
    }
}
