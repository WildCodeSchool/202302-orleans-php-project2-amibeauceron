<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Model\DogManager;

class AdminDogController extends AbstractAdminController
{
    /**
     * Display Admin home page
     */
    public function index(): string
    {
        $dogManager = new DogManager();
        $dogs = $dogManager->selectAll('name');
        return $this->twig->render('Admin/Relation/index.html.twig', ['dogs' => $dogs]);
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

    // delete file (on delete and l'update)
    private function deleteFile(?string $fileName)
    {
        if (!empty($fileName) && file_exists(__DIR__ . '/../../public/uploads/' . $fileName)) {
            unlink(__DIR__ . '/../../public/uploads/' . $fileName);
        }
    }
}
