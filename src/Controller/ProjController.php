<?php

namespace App\Controller;

use App\Proj\Blackjack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjController extends AbstractController
{
    #[Route('/proj', name: "proj")]
    public function proj(SessionInterface $session): Response
    {
        $session->clear();
        return $this->render('proj/index.html.twig');
    }

    #[Route('/proj/about', name: "proj/about")]
    public function projAbout(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    #[Route('/proj/game', name: "proj/game")]
    public function projGame(Request $request, SessionInterface $session): Response
    {
        $numPlayers = $session->get("numPlayers");
        $blackjack = $session->get("blackjack");

        if (!$numPlayers) {
            $numPlayers = (int)$request->query->get('numPlayers', 1);
            $session->set("numPlayers", $numPlayers);
        }

        if (!$blackjack) {
            $blackjack = new Blackjack($numPlayers);
            $session->set("blackjack", $blackjack);
            $blackjack->startGame();
        }

        $playerHands = $blackjack->getPlayerHands();
        $dealerHand = $blackjack->getDealerHand();

        $playerHandsValue = [];
        $dealerHandValue = $blackjack->calculateHandValue($dealerHand);

        foreach ($playerHands as $playerHand) {
            $playerHandsValue[] = $blackjack->calculateHandValue($playerHand);
        }

        $results = [];

        if ($blackjack->allPlayersStood()) {
            $blackjack->dealerPlay();
            $dealerHand = $blackjack->getDealerHand();
            $dealerHandValue = $blackjack->calculateHandValue($dealerHand);

            $results = $blackjack->determineWinners();
        }

        return $this->render('proj/game.html.twig', [
            'playerHands' => $playerHands,
            'dealerHand' => $dealerHand,
            'playerHandsValue' => $playerHandsValue,
            'dealerHandValue' => $dealerHandValue,
            'numPlayers' => $numPlayers,
            'results' => $results,
            'playersStood' => $blackjack->getPlayersStood(),
        ]);
    }

    #[Route('/proj/hit/{player}', name: "proj/hit")]
    public function projGameHit(SessionInterface $session, int $player): Response
    {
        $blackjack = $session->get("blackjack");
        $blackjack->hitPlayer($player);
        $session->set("blackjack", $blackjack);

        return $this->redirectToRoute('proj/game');
    }

    #[Route('/proj/stand/{player}', name: "proj/stand")]
    public function projGameStand(SessionInterface $session, int $player): Response
    {
        $blackjack = $session->get("blackjack");
        $blackjack->standPlayer($player);
        $session->set("blackjack", $blackjack);

        return $this->redirectToRoute('proj/game');
    }

    #[Route('/proj/double/{player}', name: "proj/double")]
    public function projGameDouble(SessionInterface $session, int $player): Response
    {
        $blackjack = $session->get("blackjack");
        $blackjack->doublePlayer($player);
        $session->set("blackjack", $blackjack);

        return $this->redirectToRoute('proj/game');
    }

    #[Route('/proj/session/delete/{numPlayers}', name: "proj/session/delete")]
    public function sessionDeleteGame(SessionInterface $session, int $numPlayers): Response
    {
        $session->clear();
        $session->set("numPlayers", $numPlayers);
        return $this->redirectToRoute('proj/game');
    }
}
