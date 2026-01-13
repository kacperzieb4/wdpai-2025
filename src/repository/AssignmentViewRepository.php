<?php

require_once 'Repository.php';

class AssignmentViewRepository extends Repository
{
    public function getAssignment($id)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT * FROM assignments WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getComments($assignmentId)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT c.content, c.created_at, u.firstname, u.lastname
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.assignment_id = :id
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([':id' => $assignmentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComment($assignmentId, $userId, $content)
    {
        $stmt = $this->database->connect()->prepare("
            INSERT INTO comments (assignment_id, user_id, content)
            VALUES (:a, :u, :c)
        ");
        $stmt->execute([
            ':a' => $assignmentId,
            ':u' => $userId,
            ':c' => $content
        ]);
    }
}
