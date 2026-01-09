<?php

require_once 'Repository.php';

class CommentRepository extends Repository {

    public function getByAssignmentId($id)
    {
        $stmt = $this->database->connect()->prepare(
            "SELECT c.*, u.firstname FROM comments c
             JOIN users u ON c.user_id = u.id
             WHERE assignment_id = :id
             ORDER BY created_at ASC"
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComment($assignmentId, $userId, $content)
    {
        $stmt = $this->database->connect()->prepare(
            "INSERT INTO comments (assignment_id, user_id, content)
             VALUES (:a, :u, :c)"
        );

        $stmt->execute([
            ':a' => $assignmentId,
            ':u' => $userId,
            ':c' => $content
        ]);
    }
}
