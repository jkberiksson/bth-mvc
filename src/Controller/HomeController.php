<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: "home")]
    public function home(): Response
    {

        $name = "Jakob Eriksson";
        $age = 28;
        $city = "Kalmar";

        $data = [
            "name" => $name,
            "age" => $age,
            "city" => $city
        ];

        return $this->render('home.html.twig', $data);
    }
}
