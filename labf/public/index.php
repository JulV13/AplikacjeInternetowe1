<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

$config = new \App\Service\Config();

$templating = new \App\Service\Templating();
$router = new \App\Service\Router();

$action = $_REQUEST['action'] ?? null;
switch ($action) {
    case 'post-index':
    case null:
        $controller = new \App\Controller\PostController();
        $view = $controller->indexAction($templating, $router);
        break;
    case 'post-create':
        $controller = new \App\Controller\PostController();
        $view = $controller->createAction($_REQUEST['post'] ?? null, $templating, $router);
        break;
    case 'post-edit':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->editAction($_REQUEST['id'], $_REQUEST['post'] ?? null, $templating, $router);
        break;
    case 'post-show':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->showAction($_REQUEST['id'], $templating, $router);
        break;
    case 'post-delete':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->deleteAction($_REQUEST['id'], $router);
        break;

    case 'post-comments-index': // Lista komentarzy dla konkretnego posta
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\CommentController();
        // Przekazujemy ID Posta
        $view = $controller->indexForPostAction($_REQUEST['id'], $templating, $router);
        break;

    case 'post-comments-show': // Podgląd komentarza (Wymagane ID Posta i ID Komentarza)
        if (! $_REQUEST['id'] || ! $_REQUEST['commentId']) {
            break;
        }
        $controller = new \App\Controller\CommentController();
        // W showAction przekazujemy tylko ID komentarza, ale ID Posta jest wymagane w routingu (dla spójności)
        $view = $controller->showAction($_REQUEST['commentId'], $templating, $router);
        break;

    case 'post-comments-create': // Tworzenie komentarza dla konkretnego posta
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\CommentController();
        // Przekazujemy ID Posta i dane formularza
        $view = $controller->createAction($_REQUEST['id'], $_REQUEST['comment'] ?? null, $templating, $router);
        break;

    case 'post-comments-edit': // Edycja komentarza
        if (! $_REQUEST['id'] || ! $_REQUEST['commentId']) {
            break;
        }
        $controller = new \App\Controller\CommentController();
        // Przekazujemy ID Komentarza i dane formularza
        $view = $controller->editAction($_REQUEST['commentId'], $_REQUEST['comment'] ?? null, $templating, $router);
        break;

    case 'post-comments-delete': // Kasowanie komentarza
        if (! $_REQUEST['id'] || ! $_REQUEST['commentId']) {
            break;
        }
        $controller = new \App\Controller\CommentController();
        // Przekazujemy ID Komentarza i ID Posta (do przekierowania)
        $view = $controller->deleteAction($_REQUEST['commentId'], $router, $_REQUEST['id']);
        break;

    case 'info':
        $controller = new \App\Controller\InfoController();
        $view = $controller->infoAction();
        break;
    default:
        $view = 'Not found';
        break;
}

if ($view) {
    echo $view;
}
