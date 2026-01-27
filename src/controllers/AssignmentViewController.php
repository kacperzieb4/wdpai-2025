<?php

require_once 'Database.php';

class AssignmentViewController
{
    public function show($id)
    {
        if (!$id) {
            die('Brak ID assignmentu');
        }

        $db = new Database();
        $conn = $db->connect();

        // ASSIGNMENT
        $stmt = $conn->prepare("
            SELECT * FROM assignments WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
        $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$assignment) {
            die('Assignment not found');
        }

        // COMMENTS
        $stmt = $conn->prepare("
            SELECT 
                c.content,
                c.created_at,
                c.video_timestamp,
                u.firstname || ' ' || u.lastname AS user_name
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.assignment_id = :id
            ORDER BY c.created_at DESC
        ");
        $stmt->execute(['id' => $id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'public/views/assignment-view.html';
    }

    public function addComment()
    {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("
            INSERT INTO comments (assignment_id, user_id, content, video_timestamp)
            VALUES (:assignment_id, :user_id, :content, :video_timestamp)
        ");

        $stmt->execute([
            'assignment_id' => $_POST['assignment_id'],
            'user_id' => $_SESSION['user_id'],
            'content' => $_POST['content'],
            'video_timestamp' => $_POST['video_timestamp'] !== '' ? $_POST['video_timestamp'] : null
        ]);

        header('Location: /assignment/' . $_POST['assignment_id']);
    }
}
