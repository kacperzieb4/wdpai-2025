<?php

require_once 'Repository.php';

class AssignmentRepository extends Repository
{
    public function getAll()
    {
        $stmt = $this->database->connect()->prepare("
            SELECT a.*, c.name AS company
            FROM assignments a
            LEFT JOIN companies c ON a.company_id = c.id
            ORDER BY a.updated_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAssignment(int $id)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT * FROM assignments WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getUserAssignments($userId)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT a.*
            FROM assignments a
            JOIN assignment_users au ON a.id = au.assignment_id
            WHERE au.user_id = :uid
            ORDER BY a.updated_at DESC
        ");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($title, $description, $videoPath, $companyId)
    {
        $stmt = $this->database->connect()->prepare("
            INSERT INTO assignments (title, description, video_path, company_id)
            VALUES (:title, :desc, :video, :company)
        ");

        $stmt->execute([
            ':title' => $title,
            ':desc' => $description,
            ':video' => $videoPath,
            ':company' => $companyId
        ]);
    }

    public function assignToUser($assignmentId, $userId)
    {
        $stmt = $this->database->connect()->prepare("
            INSERT INTO assignment_users (assignment_id, user_id)
            VALUES (:a, :u)
        ");

        $stmt->execute([
            ':a' => $assignmentId,
            ':u' => $userId
        ]);
    }
}
