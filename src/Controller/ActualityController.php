<?php

namespace App\Controller;

use App\Model\ActualityManager;

class ActualityController extends AbstractController
{
    // On détermine le nombre d'articles par page
    private const ROWS_PER_PAGE = 3;
    private int $currentPage = 1;
    private int $totalPages = 1;
    private int $firstRow = 1;
    private int $countRecords = 0;
    private array $pages = [];
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

        $this->getPagination();

        $actualityManager = new ActualityManager();
        $actualities = $actualityManager->selectAllWithPagination(
            $this->firstRow,
            $this::ROWS_PER_PAGE,
            'creation_date'
        );

        $pagination = [
            'pages' => $this->pages,
            'countRecords' => $this->countRecords,
            'currentPage' => $this->currentPage
        ];

        return $this->twig->render(
            'Admin/Actuality/index.html.twig',
            [
                'actualities' => $actualities,
                'pagination' => $pagination
            ]
        );
    }

    private function getPagination(): void
    {
        $this->currentPage = 1;
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $this->currentPage = (int) strip_tags($_GET['page']);
        }
        $actualityManager = new ActualityManager();
        // On recupère le nombre total d'enregistrements de la table
        $this->countRecords = $actualityManager->getCount();
        // On calcule le nombre de pages total
        $this->totalPages = (int) ceil($this->countRecords / $this::ROWS_PER_PAGE);
        // Calcul du 1er item de la page
        $this->firstRow = ($this->currentPage * $this::ROWS_PER_PAGE) - $this::ROWS_PER_PAGE;
        // génération d'un tableau de pages de 1 au nombre total de pages calculées
        $this->pages = range(1, $this->totalPages);
    }
}
