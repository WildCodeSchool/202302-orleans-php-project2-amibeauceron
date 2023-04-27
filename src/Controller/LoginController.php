<?php

namespace App\Controller;

use App\Model\UserManager;

class LoginController extends AbstractController
{
    public function login(): ?string
    {
        $user = [];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $user = array_map('trim', $_POST);

            // Validations
            $errors = $this->validateData($user);

            // if validation is ok
            // check User allowed to connect
            if (empty($errors)) {
                if ($this->isAuthorize($user)) {
                    //connection is OK, redirection
                    header('Location: /administration');
                    // we are redirecting so we don't want any content rendered
                    return null;
                }
                $errors[] = "Email ou password incorrect !";
            }
        }
        return $this->twig->render('Login/login.html.twig', ['user' => $user, 'errors' => $errors]);
    }

    private function isAuthorize($user): bool
    {
        $userManager = new UserManager();
        $dbUser = $userManager->selectOneByEmail($user['email']);
        if (!empty($dbUser) && password_verify($user['password'], $dbUser['password'])) {
            //add user to session
            $_SESSION['user_id'] = $dbUser['id'];
            //add user to global twig variable
            $this->twig->addGlobal('user_id', $dbUser['id']);
            return true;
        }
        return false;
    }

    private function validateData(array $user): array
    {
        $errors = [];
        if (empty($user['email'])) {
            $errors[] = "Veuillez renseigner votre email, zone obligatoire.";
        }

        if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            $errors = "Le format de votre email n'est pas valide";
        }

        if (empty($user['password'])) {
            $errors[] = "Veuillez renseigner votre password, zone obligatoire.";
        }
        return $errors;
    }
}
