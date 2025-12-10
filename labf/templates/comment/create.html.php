<?php

/** @var \App\Model\Comment $comment */
/** @var \App\Model\Post $post */
/** @var \App\Service\Router $router */

$title = 'Create Comment for ' . $post->getSubject();
$bodyClass = "edit comment";

ob_start(); ?>
    <h1>Create Comment for: <?= $post->getSubject() ?></h1>

    <form action="<?= $router->generatePath('post-comments-create') ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>

        <input type="hidden" name="action" value="post-comments-create">
        <input type="hidden" name="id" value="<?= $post->getId() ?>">
    </form>

    <a href="<?= $router->generatePath('post-comments-index', ['id' => $post->getId()]) ?>">Back to comments list</a>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';