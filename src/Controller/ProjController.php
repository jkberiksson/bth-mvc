<?php

namespace App\Controller;

use App\Proj\Blackjack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjController extends AbstractController
{
    #[Route('/proj', name: "proj")]
    public function proj(): Response
    {
        return $this->render('proj/index.html.twig');
    }

    #[Route('/proj/about', name: "proj/about")]
    public function projAbout(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    #[Route('/proj/game', name: "proj/game")]
    public function projGame(Request $request): Response
    {
        $numPlayers = (int)$request->query->get('numPlayers', 1);

        $blackjack = new Blackjack($numPlayers);
        $blackjack->startGame();

        $playerHands = $blackjack->getPlayerHands();
        $dealerHand = $blackjack->getDealerHand();

        $playerHandsValue = [];
        $dealerHandValue = $blackjack->calculateHandValue($dealerHand);

        foreach ($playerHands as $playerHand) {
            $playerHandsValue[] = $blackjack->calculateHandValue($playerHand);
        }

        return $this->render('proj/game.html.twig', [
            'playerHands' => $playerHands,
            'dealerHand' => $dealerHand,
            'playerHandsValue' => $playerHandsValue,
            'dealerHandValue' => $dealerHandValue,
            'numPlayers' => $numPlayers,
        ]);
    }
}
