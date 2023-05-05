<?php

namespace App\Controller;

use App\Model\MemberManager;

class AdminMemberController extends AbstractController
{
    public function index(): string
    {
        $memberManager = new MemberManager();
        $members = $memberManager->selectAll('lastname');

        return $this->twig->render('Admin/Member/index.html.twig', ['members' => $members]);
    }

    public function update(int $id): string
    {
        $memberManager = new MemberManager();
        $member = $memberManager->selectOneById($id);

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member = array_map('trim', $_POST);

            $emptyErrors = $this->validateEmpty($member);
            $strlenErrors = $this->validateStrlen($member);
            $uploadErrors = $this->validateUpload($_FILES);

            $errors = array_merge($emptyErrors, $strlenErrors, $uploadErrors);

            if (empty($errors)) {
                $imageName = $this->generateImageName($_FILES['image']);

                $member['image'] = $imageName;

                $memberManager = new MemberManager();
                $memberManager->update($member);

                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public/uploads/'  . $imageName);
            }
            header('Location: /administration/membres');
        }

        return $this->twig->render('Admin/Member/update.html.twig', [
            'member' => $member,
        ]);
    }
    public function add(): string
    {
        $errors = $member = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member = array_map('trim', $_POST);

            $emptyErrors = $this->validateEmpty($member);
            $strlenErrors = $this->validateStrlen($member);
            $uploadErrors = $this->validateUpload($_FILES);

            $errors = array_merge($emptyErrors, $strlenErrors, $uploadErrors);

            if (empty($errors)) {
                $imageName = $this->generateImageName($_FILES['image']);

                $member['image'] = $imageName;

                $memberManager = new MemberManager();
                $memberManager->insert($member);

                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public/uploads/'  . $imageName);
            }
            header('Location: /administration/membres');
        }

        return $this->twig->render('Admin/Member/add.html.twig', [
            'errors' => $errors,
            'member' => $member,
        ]);
    }

    private function validateEmpty(array $member): array
    {
        $errors = [];
        if (empty($member['lastname'])) {
            $errors[] = 'Le champ nom est obligatoire';
        }
        if (empty($member['firstname'])) {
            $errors[] = 'Le champ prénom est obligatoire';
        }
        if (empty($member['job'])) {
            $errors[] = 'Le champ post occupé est obligatoire';
        }
        if (empty($member['email'])) {
            $errors[] = 'Le champ email est obligatoire';
        }
        return $errors;
    }

    private function validateStrlen(array $member): array
    {
        $errors = [];
        $maxLength = 100;
        if (mb_strlen($member['firstname']) > $maxLength) {
            $errors[] = 'Le champ prénom doit faire moins de ' . $maxLength . ' caractères';
        }
        if (mb_strlen($member['lastname']) > $maxLength) {
            $errors[] = 'Le champ nom doit faire moins de ' . $maxLength . ' caractères';
        }
        if (mb_strlen($member['job']) > $maxLength) {
            $errors[] = 'Le champ poste occupé doit faire moins de ' . $maxLength . ' caractères';
        }
        if (mb_strlen($member['email']) > $maxLength) {
            $errors[] = 'Le champ email doit faire moins de ' . $maxLength . ' caractères';
        }

        return $errors;
    }

    private function validateUpload(array $files): array
    {
        $errors = [];
        if ($files['image']['name'] && $files['image']['error'] !== 0) {
            $errors[] = 'Problème avec l\'upload, veuillez réessayer';
        } elseif ($files['image']['name']) {
            $limitFileSize = '1000000';
            if ($files['image']['size'] > $limitFileSize) {
                $errors[] = 'Le fichier doit faire moins de ' . $limitFileSize / 1000000 . 'Mo';
            }

            $authorizedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array(mime_content_type($files['image']['tmp_name']), $authorizedMimes)) {
                $errors[] = 'Le type de fichier est incorrect. Types autorisées : ' . implode(', ', $authorizedMimes);
            }
        }

        return $errors;
    }

    private function generateImageName(array $files)
    {
        $extension = pathinfo($files['name'], PATHINFO_EXTENSION);
        $baseFilename = pathinfo($files['name'], PATHINFO_FILENAME);
        return uniqid($baseFilename, more_entropy: true) . '.' . $extension;
    }
}
