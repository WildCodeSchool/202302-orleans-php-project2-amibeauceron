<?php

namespace App\Controller;

use App\Model\MembershipManager;

class MembershipController extends AbstractController
{
    public const MAX_LENGTH_NAME = 100;
    public const MAX_LENGTH_LASTNAME = 100;
    public const MAX_LENGTH_ADRESS = 100;
    public const MAX_LENGTH_TEL = 100;
    public const MAX_LENGTH_EMAIL = 100;

    public function add(): string
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $membership = array_map('trim', $_POST);
            $dataErrors = $this->validateData($membership);
            $lenghtErrors = $this->validateLenght($membership);
            $errors = array_merge($dataErrors, $lenghtErrors);
            if (empty($errors)) {
                header('Location:/administration/actualites');
                exit();
            }
            return $this->twig->render('Membership/index.html.twig', [
                'membership' => $membership,
                'errors' => $errors
            ]);
        }
        return $this->twig->render('Membership/index.html.twig');
    }

    private function validateData(array $membership): array
    {
        $errors = [];

        if (empty($membership['name'])) {
            $errors[] = "Veuillez renseigner le nom, zone obligatoire.";
        }

        if (empty($membership['lastname'])) {
            $errors[] = "Veuillez renseigner le prénom, zone obligatoire.";
        }
        if (empty($membership['adress'])) {
            $errors[] = "Veuillez renseigner l'adresse, zone obligatoire.";
        }

        if (empty($membership['tel'])) {
            $errors[] = "Veuillez renseigner le telephone, zone obligatoire.";
        }

        if (empty($membership['email'])) {
            $errors[] = "Veuillez renseigner le courriel, zone obligatoire.";
        }

        return $errors;
    }


    private function validateLenght(array $membership): array
    {
        $errors = [];

        if (mb_strlen(($$membership['name'])) > self::MAX_LENGTH_NAME) {
            $errors[] = "Le nom doit faire un maximum de " . self::MAX_LENGTH_NAME .
                " caractères (actuellement: " . mb_strlen($membership['name']) . ")";
        }
        if (mb_strlen(($membership['lastname'])) > self::MAX_LENGTH_LASTNAME) {
            $errors[] = "Le prenom doit faire un maximum de " . self::MAX_LENGTH_LASTNAME .
                " caractères (actuellement: " . mb_strlen($membership['prenom']) . ")";
        }
        if (mb_strlen(($$membership['adress'])) > self::MAX_LENGTH_ADRESS) {
            $errors[] = "L'adresse doit faire un maximum de " . self::MAX_LENGTH_ADRESS .
                " caractères (actuellement: " . mb_strlen($membership['adress']) . ")";
        }
        if (mb_strlen(($$membership['tel'])) > self::MAX_LENGTH_TEL) {
            $errors[] = "Le telephone doit faire un maximum de " . self::MAX_LENGTH_TEL .
                " caractères (actuellement: " . mb_strlen($membership['tel']) . ")";
        }
        if (mb_strlen(($$membership['email'])) > self::MAX_LENGTH_EMAIL) {
            $errors[] = "Le courriel doit faire un maximum de " . self::MAX_LENGTH_EMAIL .
                " caractères (actuellement: " . mb_strlen($membership['email']) . ")";
        }

        return $errors;
    }
}
