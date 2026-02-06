<?php

require_once 'Repository.php';

class CommentRepository extends Repository
{
    /**
     * Wszystkie komentarze do assignmentu
     * (potrzebne: user_id + user_name do uprawnień)
     */
    public function getByAssignmentId(int $assignmentId): array
    {
        $stmt = $this->database->connect()->prepare("
            SELECT
                c.id,
                c.assignment_id,
                c.user_id,
                c.content,
                c.video_timestamp,
                c.created_at,
                u.firstname || ' ' || u.lastname AS user_name
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.assignment_id = :id
            ORDER BY c.created_at ASC
        ");

        $stmt->execute([':id' => $assignmentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Pojedynczy komentarz (edit / delete)
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->database->connect()->prepare("
            SELECT * FROM comments WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Dodanie komentarza
     */
    public function addComment(
        int $assignmentId,
        int $userId,
        string $content,
        ?int $timestamp = null
    ): void {
        $stmt = $this->database->connect()->prepare("
            INSERT INTO comments (assignment_id, user_id, content, video_timestamp)
            VALUES (:a, :u, :c, :t)
        ");

        $stmt->execute([
            ':a' => $assignmentId,
            ':u' => $userId,
            ':c' => $content,
            ':t' => $timestamp
        ]);
    }

    /**
     * Edycja komentarza
     */
    public function update(int $id, string $content): void
    {
        $stmt = $this->database->connect()->prepare("
            UPDATE comments
            SET content = :c
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id,
            ':c' => $content
        ]);
    }

    /**
     * Usunięcie komentarza
     */
    public function delete(int $id): void
    {
        $stmt = $this->database->connect()->prepare("
            DELETE FROM comments WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
    }
}
