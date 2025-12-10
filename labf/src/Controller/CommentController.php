<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Comment;
use App\Model\Post;
use App\Service\Router;
use App\Service\Templating;

class CommentController
{
    public function indexForPostAction(int $postId, Templating $templating, Router $router): ?string
    {
        $post = Post::find($postId);
        if (!$post) {
            throw new NotFoundException("Missing post with id $postId");
        }

        $comments = Comment::findAllForPost($postId);

        $html = $templating->render('comment/index.html.php', [
            'post' => $post,
            'comments' => $comments,
            'router' => $router,
        ]);
        return $html;
    }

    public function showAction(int $commentId, Templating $templating, Router $router): ?string
    {
        $comment = Comment::find($commentId);
        if (! $comment) {
            throw new NotFoundException("Missing comment with id $commentId");
        }

        $post = Post::find($comment->getPostId());
        if (!$post) {
            throw new NotFoundException("Missing related post.");
        }

        $html = $templating->render('comment/show.html.php', [
            'post' => $post,
            'comment' => $comment,
            'router' => $router,
        ]);
        return $html;
    }

    public function createAction(int $postId, ?array $requestComment, Templating $templating, Router $router): ?string
    {
        $post = Post::find($postId);
        if (!$post) {
            throw new NotFoundException("Missing post with id $postId");
        }

        if ($requestComment) {
            $comment = Comment::fromArray($requestComment);

            $comment->setPostId($postId);

            if ($comment->getPostId() && $comment->getAuthor() && $comment->getContent()) {
                $comment->save();
                $path = $router->generatePath('post-comments-index', ['id' => $postId]);
                $router->redirect($path);
                return null;
            }
        } else {
            $comment = new Comment();
            $comment->setPostId($postId);
        }

        $html = $templating->render('comment/create.html.php', [
            'post' => $post,
            'comment' => $comment,
            'router' => $router,
        ]);
        return $html;
    }

    public function editAction(int $commentId, ?array $requestComment, Templating $templating, Router $router): ?string
    {
        $comment = Comment::find($commentId);
        if (! $comment) {
            throw new NotFoundException("Missing comment with id $commentId");
        }

        $postId = $comment->getPostId();
        $post = Post::find($postId);
        if (!$post) {
            throw new NotFoundException("Missing related post with id $postId");
        }

        if ($requestComment) {
            $comment->fill($requestComment);
            $comment->setPostId($postId);

            if ($comment->getPostId() && $comment->getAuthor() && $comment->getContent()) {
                $comment->save();
                $path = $router->generatePath('post-comments-index', ['id' => $postId]);
                $router->redirect($path);
                return null;
            }
        }

        $html = $templating->render('comment/edit.html.php', [
            'post' => $post,
            'comment' => $comment,
            'router' => $router
        ]);
        return $html;
    }

    public function deleteAction(int $commentId, Router $router, ?int $postId = null): ?string
    {
        $comment = Comment::find($commentId);
        if (! $comment) {
            throw new NotFoundException("Missing comment with id $commentId");
        }

        $redirectPostId = $postId ?? $comment->getPostId();

        $comment->delete();

        $path = $router->generatePath('post-comments-index', ['id' => $redirectPostId]);
        $router->redirect($path);
        return null;
    }
}