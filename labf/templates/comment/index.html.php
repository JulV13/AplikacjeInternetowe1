<?php

/** @var \App\Model\Comment[] $comments */
/** @var \App\Model\Post $post */
/** @var \App\Service\Router $router */

$title = 'Comments for: ' . $post->getSubject();
$bodyClass = 'index comment';

ob_start(); ?>
    <h1>Comments for: <?= $post->getSubject() ?></h1>

    <ul class="action-list">
        <li><a href="<?= $router->generatePath('post-comments-create', ['id' => $post->getId()]) ?>">Create a new comment for this post</a></li>
        <li><a href="<?= $router->generatePath('post-show', ['id' => $post->getId()]) ?>">Back to Post details</a></li>
    </ul>

    <ul class="index-list comment">
        <?php if (empty($comments)): ?>
            <p>No comments related to this post!</p>
        <?php endif; ?>

        <?php foreach ($comments as $comment): ?>
            <li class="comment-item">
                <div class="comment-meta">
                    <strong><?= $comment->getAuthor() ?></strong> at <?= $comment->getCreatedAt() ?>
                </div>
                <div class="comment-content"><?= substr($comment->getContent(), 0, 150) ?>...</div>

                <ul class="action-list">
                    <li><a href="<?= $router->generatePath('post-comments-show', ['id' => $post->getId(), 'commentId' => $comment->getId()]) ?>">Details</a></li>
                    <li><a href="<?= $router->generatePath('post-comments-edit', ['id' => $post->getId(), 'commentId' => $comment->getId()]) ?>">Edit</a></li>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';