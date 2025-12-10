<?php

/** @var \App\Model\Comment $comment */
/** @var \App\Model\Post $post */
/** @var \App\Service\Router $router */

$title = "Comment {$comment->getId()}";
$bodyClass = 'show comment';

ob_start(); ?>
    <h1>Comment by <?= $comment->getAuthor() ?></h1>
    <p>Related Post: <a href="<?= $router->generatePath('post-show', ['id' => $post->getId()]) ?>"><?= $post->getSubject() ?></a></p>

    <article>
        <p><strong>Created At:</strong> <?= $comment->getCreatedAt() ?></p>
        <hr>
        <p><?= nl2br($comment->getContent()) ?></p>
    </article>

    <ul class="action-list">
        <li> <a href="<?= $router->generatePath('post-comments-index', ['id' => $post->getId()]) ?>">Back to comments list</a></li>
        <li><a href="<?= $router->generatePath('post-comments-edit', ['id'=> $post->getId(), 'commentId' => $comment->getId()]) ?>">Edit</a></li>
    </ul>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';