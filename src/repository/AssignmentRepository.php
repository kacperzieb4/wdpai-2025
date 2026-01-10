<?php

require_once 'Repository.php';

class AssignmentRepository extends Repository {

    public function getById($id)
    {
        $stmt = $this->database->connect()->prepare(
            "SELECT * FROM assignments WHERE id = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getForUser($userId)
    {
        $stmt = $this->database->connect()->prepare(
            "SELECT a.* FROM assignments a
             JOIN assignment_users au ON a.id = au.assignment_id
             WHERE au.user_id = :uid"
        );
        $stmt->bindParam(':uid', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createAssignment($title, $description, $videoPath)
    {
        $stmt = $this->database->connect()->prepare(
            "INSERT INTO assignments (title, description, video_path)
            VALUES (:t, :d, :v)
            RETURNING id"
        );

        $stmt->execute([
            ':t' => $title,
            ':d' => $description,
            ':v' => $videoPath
        ]);

        return $stmt->fetchColumn();
    }

    public function assignUser($assignmentId, $userId)
    {
        $stmt = $this->database->connect()->prepare(
            "INSERT INTO assignment_users (assignment_id, user_id)
            VALUES (:a, :u)"
        );

        $stmt->execute([
            ':a' => $assignmentId,
            ':u' => $userId
        ]);
    }

}
