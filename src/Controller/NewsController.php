<?php

namespace App\Controller;

use App\Model\NewsManager;

class NewsController extends AbstractController
{
    /**
     * List news
     */
    public function index(): string
    {
        $newsManager = new NewsManager();
        $newslist = $newsManager->selectAll('creation_date');

        return $this->twig->render('News/index.html.twig', ['newslist' => $newslist]);
    }

    /**
     * Show informations for a specific news
     */
    public function show(int $id): string
    {
        $newsManager = new NewsManager();
        $news = $newsManager->selectOneById($id);

        return $this->twig->render('News/show.html.twig', ['news' => $news]);
    }
}
