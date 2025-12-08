<?php

require_once __DIR__ . '/../repository/CardsRepository.php';

class CardsController
{
    private $cardsRepository;

    public function __construct()
    {
        $this->cardsRepository = new CardsRepository();
    }

    public function search()
    {
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            header('Content-Type: application/json');
            http_response_code(200);

            echo json_encode(
                $this->cardsRepository->getCardsByTitle($decoded['search'])
            );
            exit();
        }

        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
    }
}
