<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JsonApiController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function jsonRoutes(): Response
    {
        $data = [
            'routes' => [
                "/" => "Home",
                "/about" => "About",
                "/report" => "Report",
                "/lucky" => "lucky",
                "/api" => "Api",
                "/api/qoute" => "JSON Api qoute",
            ],
        ];

        return $this->render('api.html.twig', $data);
    }

    #[Route("/api/qoute")]
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
