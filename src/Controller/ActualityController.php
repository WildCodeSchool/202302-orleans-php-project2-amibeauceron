<?php

namespace App\Controller;

use App\Model\ActualityManager;

class ActualityController extends AbstractController
{
    // On détermine le nombre d'articles par page
    private const ROWS_PER_PAGE = 3;

    /**
     * List of Actualities
     */
    public function index(): string
    {
        $actualityManager = new ActualityManager();
        $actualities = $actualityManager->selectAll('creation_date');
        return $this->twig->render('Actuality/index.html.twig', ['actualities' => $actualities]);
    }

    public function gestion(): string
    {
        //check user id admin and exist
        if (!$this->user || $this->user['Role'] != 'Admin') {
            echo 'Unauthorized access';
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }

        $currentPage = 1;
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $currentPage = (int) strip_tags($_GET['page']);
        }

        $actualityManager = new ActualityManager();
        $nbrActualities = $actualityManager->getCount();

        // On calcule le nombre de pages total
        $totalPages = ceil($nbrActualities / $this::ROWS_PER_PAGE);
        // Calcul du 1er item de la page
        $firstRow = ($currentPage * $this::ROWS_PER_PAGE) - $this::ROWS_PER_PAGE;
        // génération d'un tableau de pages de 1 au nombre total de pages calculées
        $pages = range(1, $totalPages);
        $actualities = $actualityManager->selectAllWithPagination($firstRow, $this::ROWS_PER_PAGE, 'creation_date');
        return $this->twig->render('Admin/Actuality/index.html.twig', ['actualities' => $actualities, 'pages' => $pages, 'nbrActualities' => $nbrActualities, 'currentPage' => $currentPage]);
    }
}
