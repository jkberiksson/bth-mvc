<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JsonApiController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function jsonRoutes(): Response
    {
        $data = [
            'routes' => [
                "api/qoute" => "Method: GET | Slumpar fram ett citat av tre möjliga och returnerar det tillsammans med dagens datum och timestamp.",
                "api/deck" => "Method: GET | Returnerar sorterad kortlek.",
                "api/deck/shuffle" => "Method: POST | Blandar kortleken och returnerar den samt spara i sessionen.",
                "api/deck/draw/:number" => "Method: POST | Drar antalet kort från kortleken och returnerar dom dragna korten samt antalet kort som finns kvar i kortleken.",
                "api/game" => "Method: GET | Visar upp aktuell ställning i blackjack.",
                "api/library/books" => "Method: GET | Visar upp samtliga böcker i biblioteket.",
                "api/library/book/:isbn" => "Method: GET | Visa upp en bok i biblioteket via dess ISBN.",
                "api/library/book/978045126584" => "",
            ],
        ];

        return $this->render('api.html.twig', $data);
    }

    #[Route("/api/qoute", name: "api/qoute", methods: ["GET"])]
    public function jsonQoute(): Response
    {
        $number = random_int(0, 2);

        $quotes = [
            "The only way to do great work is to love what you do. - Steve Jobs",
            "Success is not final, failure is not fatal: It is the courage to continue that counts. - Winston Churchill",
            "In the end, its not the years in your life that count. It's the life in your years. - Abraham Lincoln"
        ];

        $data = [
            'quote' => $quotes[$number],
            'date' => date('Y-m-d'),
            'timestamp' => time(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
