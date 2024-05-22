<?php

namespace App\Controller;

use App\Blackjack\Blackjack;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameController extends AbstractController
{
    #[Route('/game', name: "game")]
    public function game(SessionInterface $session): Response
    {
        $session->clear();

        $blackjack = new Blackjack();
        $blackjack->startGame();
        $session->set('blackjack', $blackjack);

        return $this->render('game/game.html.twig');
    }

    #[Route('/game/play', name: "game/play")]
    public function gamePlay(SessionInterface $session): Response
    {
        $blackjack = $session->get('blackjack');

        $playerHand = $blackjack->getPlayerHand();
        $dealerHand = $blackjack->getDealerHand();

        $playerHandValue = $blackjack->calculateHandValue($playerHand);
        $dealerHandValue = $blackjack->calculateHandValue($dealerHand);

        if ($playerHandValue > 21 || $dealerHandValue >= 17) {
            $winnerMsg = $blackjack->calculateWinner($playerHandValue, $dealerHandValue);
            $session->set("winner", $winnerMsg);
            $this->addFlash('success', $winnerMsg);
        }

        $data = [
            'playerHand' => $playerHand,
            'dealerHand' => $dealerHand,
            'playerHandValue' => $playerHandValue,
            'dealerHandValue' => $dealerHandValue,
        ];

        return $this->render('game/game-play.html.twig', $data);
    }

    #[Route('/game/play/post', name: "game/play/post", methods: ["POST"])]
    public function gamePlayPost(Request $request, SessionInterface $session): Response
    {
        $blackjack = $session->get('blackjack');

        $action = $request->request->get('action');

        $dealerHand = $blackjack->getDealerHand();
        $dealerHandValue = $blackjack->calculateHandValue($dealerHand);

        switch ($action) {
            case 'hit':
                $blackjack->hitPlayer();
                break;
            case 'stand':
                while ($dealerHandValue < 17) {
                    $blackjack->hitDealer();
                    $dealerHand = $blackjack->getDealerHand();
                    $dealerHandValue = $blackjack->calculateHandValue($dealerHand);
                }
                break;
            case 'restart':
                $session->clear();
                $blackjack = new Blackjack();
                $blackjack->startGame();
                $session->set('blackjack', $blackjack);
                return $this->redirectToRoute('game/play');
        }

        $session->set('blackjack', $blackjack);

        return $this->redirectToRoute('game/play');
    }
}
