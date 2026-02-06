<?php

require_once 'Repository.php';

class AssignmentRepository extends Repository
{

    public function create(
        string $title,
        ?string $description,
        string $videoPath,
        int $companyId
    ) {
        $stmt = $this->database->connect()->prepare("
            INSERT INTO assignments (title, description, video_path, company_id)
            VALUES (:title, :description, :video, :company)
        ");

        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':video' => $videoPath,
            ':company' => $companyId
        ]);
    }

    public function update(
        int $id,
        string $title,
        ?string $description,
        int $companyId,
        ?string $videoPath = null
    ) {
        $db = $this->database->connect();

        if ($videoPath !== null) {

            $stmt = $db->prepare("
                UPDATE assignments
                SET
                    title = :title,
                    description = :description,
                    company_id = :company,
                    video_path = :video
                WHERE id = :id
            ");

            $stmt->execute([
                ':id' => $id,
                ':title' => $title,
                ':description' => $description,
                ':company' => $companyId,
                ':video' => $videoPath
            ]);
        } else {
            $stmt = $db->prepare("
                UPDATE assignments
                SET
                    title = :title,
                    description = :description,
                    company_id = :company
                WHERE id = :id
            ");

            $stmt->execute([
                ':id' => $id,
                ':title' => $title,
                ':description' => $description,
                ':company' => $companyId
            ]);
        }
    }


    public function getAssignment(int $id)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT 
                a.id,
                a.title,
                a.description,
                a.video_path,
                a.company_id
            FROM assignments a
            WHERE a.id = :id
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllWithCompany()
    {
        $stmt = $this->database->connect()->prepare("
            SELECT 
                a.id,
                a.title,
                c.name AS company
            FROM assignments a
            LEFT JOIN companies c ON a.company_id = c.id
            ORDER BY a.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getForUserWithCompany(int $userId)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT 
                a.id,
                a.title,
                c.name AS company
            FROM assignments a
            JOIN assignment_users au ON a.id = au.assignment_id
            LEFT JOIN companies c ON a.company_id = c.id
            WHERE au.user_id = :uid
            ORDER BY a.created_at DESC
        ");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getUserAssignmentsWithCompany(int $userId)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT 
                a.id,
                a.title,
                c.name AS company
            FROM assignments a
            JOIN assignment_users au ON a.id = au.assignment_id
            LEFT JOIN companies c ON a.company_id = c.id
            WHERE au.user_id = :uid
            ORDER BY a.created_at DESC
        ");
        $stmt->execute([':uid' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDashboardAssignments(int $userId, string $role)
    {
        if ($role === 'USER') {
            $stmt = $this->database->connect()->prepare("
                SELECT 
                    a.id,
                    a.title,
                    c.name AS company
                FROM assignments a
                JOIN assignment_users au ON a.id = au.assignment_id
                LEFT JOIN companies c ON a.company_id = c.id
                WHERE au.user_id = :uid
                ORDER BY a.created_at DESC
            ");
            $stmt->execute([':uid' => $userId]);
        } else {
            $stmt = $this->database->connect()->prepare("
                SELECT 
                    a.id,
                    a.title,
                    c.name AS company
                FROM assignments a
                JOIN users u ON u.id = :uid
                LEFT JOIN companies c ON a.company_id = c.id
                WHERE a.company_id = u.company_id
                ORDER BY a.created_at DESC
            ");
            $stmt->execute([':uid' => $userId]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserAssignments(int $userId)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT 
                a.id,
                a.title,
                c.name AS company
            FROM assignments a
            JOIN assignment_users au ON a.id = au.assignment_id
            LEFT JOIN companies c ON a.company_id = c.id
            WHERE au.user_id = :uid
            ORDER BY a.created_at DESC
        ");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }






    public function delete(int $id)
    {
        $stmt = $this->database->connect()->prepare("
            DELETE FROM assignments WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
    }
}
