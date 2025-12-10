<?php

/** @var \App\Model\Comment $comment */
/** @var \App\Model\Post $post */
/** @var \App\Service\Router $router */

$title = "Edit Comment {$comment->getId()}";
$bodyClass = "edit comment";

ob_start(); ?>
    <h1><?= $title ?> (Post: <?= $post->getSubject() ?>)</h1>
    <form action="<?= $router->generatePath('post-comments-edit') ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>

        <input type="hidden" name="action" value="post-comments-edit">
        <input type="hidden" name="id" value="<?= $post->getId() ?>">
        <input type="hidden" name="commentId" value="<?= $comment->getId() ?>">
    </form>

    <ul class="action-list">
        <li>
            <a href="<?= $router->generatePath('post-comments-index', ['id' => $post->getId()]) ?>">Back to comments list</a></li>
        <li>
            <form action="<?= $router->generatePath('post-comments-delete') ?>" method="post">
                <input type="submit" value="Delete" onclick="return confirm('Are you sure?')">
                <input type="hidden" name="action" value="post-comments-delete">
                <input type="hidden" name="id" value="<?= $post->getId() ?>">
                <input type="hidden" name="commentId" value="<?= $comment->getId() ?>">
            </form>
        </li>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';