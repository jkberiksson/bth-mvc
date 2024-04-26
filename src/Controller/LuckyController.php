<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    #[Route('/lucky', name: "lucky")]
    public function lucky(): Response
    {
        $number = random_int(0, 100);
        $imgNumber = random_int(0, 2);

        $data = [
            "number" => $number,
            "img_number" => $imgNumber
        ];

        return $this->render('lucky.html.twig', $data);
    }
}
