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
    'beauceron/soin' => ['CareController', 'index',],
    'actualites'  => ['ActualityController', 'index'],
    'actualites/afficher'  => ['ActualityController', 'show', ['id']],
    'administration' => ['Admin\AdminController', 'index'],
    'administration/actualites'  => ['AdminActualityController', 'index'],
    'administration/actualites/ajouter' => ['AdminActualityController', 'add'],
    'administration/actualites/supprimer' => ['AdminActualityController', 'delete', ['id']],
    'administration/actualites/modifier' => ['AdminActualityController', 'edit', ['id']],
    'administration/membres' => ['AdminMemberController', 'index'],
    'administration/membres/ajouter' => ['AdminMemberController', 'add'],
    'connexion' => ['LoginController', 'login'],
    'evenements' => ['EventController', 'index'],
    'administration/nos-chiens' => ['AdminDogController', 'index'],
    'nos-chiens' => ['DogController', 'index'],
    'administration/evenements/ajouter' => ['AdminEventController', 'add'],
    'administration/nos-chiens/ajouter' => ['AdminDogController', 'add'],
    'administration/evenements' => ['AdminEventController', 'index'],
    'association'  => ['MemberController', 'index'],
    'administration/evenements/supprimer' => ['AdminEventController', 'delete', ['id']],
    'administration/evenements/modifier' => ['AdminEventController', 'update', ['id']],
    'administration/nos-chiens/supprimer' => ['AdminDogController', 'delete', ['id']],
];
