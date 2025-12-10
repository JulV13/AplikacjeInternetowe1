<?php
namespace App\Model;

use App\Service\Config;

class Comment
{
    private ?int $id = null;
    private ?int $postId = null;
    private ?string $author = null;
    private ?string $content = null;
    private ?string $createdAt = null;

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): Comment { $this->id = $id; return $this; }

    public function getPostId(): ?int { return $this->postId; }
    public function setPostId(?int $postId): Comment { $this->postId = $postId; return $this; }

    public function getAuthor(): ?string { return $this->author; }
    public function setAuthor(?string $author): Comment { $this->author = $author; return $this; }

    public function getContent(): ?string { return $this->content; }
    public function setContent(?string $content): Comment { $this->content = $content; return $this; }

    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function setCreatedAt(?string $createdAt): Comment { $this->createdAt = $createdAt; return $this; }

    public static function fromArray($array): Comment
    {
        $comment = new self();
        $comment->fill($array);
        return $comment;
    }

    public function fill($array): Comment
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId((int)$array['id']);
        }
        if (isset($array['postId'])) {
            $this->setPostId((int)$array['postId']);
        }
        if (isset($array['author'])) {
            $this->setAuthor($array['author']);
        }
        if (isset($array['content'])) {
            $this->setContent($array['content']);
        }
        if (isset($array['created_at'])) {
            $this->setCreatedAt($array['created_at']);
        }

        return $this;
    }

    public static function fillFromDbArray($array): Comment
    {
        $comment = new self();
        if (isset($array['id'])) {
            $comment->setId((int)$array['id']);
        }
        if (isset($array['post_id'])) { // BAZA MA 'post_id'
            $comment->setPostId((int)$array['post_id']);
        }
        if (isset($array['author'])) {
            $comment->setAuthor($array['author']);
        }
        if (isset($array['content'])) {
            $comment->setContent($array['content']);
        }
        if (isset($array['created_at'])) {
            $comment->setCreatedAt($array['created_at']);
        }
        return $comment;
    }

    public static function findAllForPost(int $postId): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $pdo->exec('PRAGMA foreign_keys = ON;');

        $sql = 'SELECT * FROM comment WHERE post_id = :postId ORDER BY created_at ASC';
        $statement = $pdo->prepare($sql);
        $statement->execute([':postId' => $postId]);

        $comments = [];
        $commentsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($commentsArray as $commentArray) {
            $comments[] = self::fillFromDbArray($commentArray);
        }

        return $comments;
    }

    public static function find($id): ?Comment
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $pdo->exec('PRAGMA foreign_keys = ON;');

        $sql = 'SELECT * FROM comment WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $commentArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $commentArray) {
            return null;
        }
        return self::fillFromDbArray($commentArray);
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $pdo->exec('PRAGMA foreign_keys = ON;');

        if (! $this->getId()) {
            $sql = "INSERT INTO comment (post_id, author, content, created_at) VALUES (:postId, :author, :content, :createdAt)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':postId' => $this->getPostId(),
                ':author' => $this->getAuthor(),
                ':content' => $this->getContent(),
                ':createdAt' => date('Y-m-d H:i:s'),
            ]);

            $this->setId($pdo->lastInsertId());
        } else {
            $sql = "UPDATE comment SET post_id = :postId, author = :author, content = :content WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':postId' => $this->getPostId(),
                ':author' => $this->getAuthor(),
                ':content' => $this->getContent(),
                ':id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void
    {
        if (!$this->getId()) {
            return;
        }
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $pdo->exec('PRAGMA foreign_keys = ON;');

        $sql = "DELETE FROM comment WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $this->getId(),
        ]);

        $this->setId(null);
    }
}