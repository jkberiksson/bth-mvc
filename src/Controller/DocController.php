<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocController extends AbstractController
{
    #[Route('/game/doc', name: "game/doc")]
    public function gameDoc(): Response
    {
        return $this->render('game-doc.html.twig');
    }
}
