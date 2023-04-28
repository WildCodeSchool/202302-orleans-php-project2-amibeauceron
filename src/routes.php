<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)

return [
    '' => ['HomeController', 'index',],
    'items' => ['ItemController', 'index',],
    'items/edit' => ['ItemController', 'edit', ['id']],
    'items/show' => ['ItemController', 'show', ['id']],
    'items/add' => ['ItemController', 'add',],
    'items/delete' => ['ItemController', 'delete',],
    'beauceron' => ['DogController', 'beauceron'],
    'actualites'  => ['ActualityController', 'index'],
    'actualites/afficher'  => ['ActualityController', 'show', ['id']],
    'administration' => ['Admin\AdminController', 'index'],
    'administration/actualites'  => ['AdminActualityController', 'index'],
    'administration/actualites/ajouter' => ['AdminActualityController', 'add'],
    'administration/actualites/supprimer' => ['AdminActualityController', 'delete'],
    'administration/actualites/modifier' => ['AdminActualityController', 'edit'],
    'evenements' => ['EventController', 'index'],
    'administration/evenements' => ['AdminEventController', 'index'],
    'administration/evenements/ajouter' => ['AdminEventController', 'add'],
];
