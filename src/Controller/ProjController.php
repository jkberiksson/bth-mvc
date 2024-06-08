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
        $numHands = $session->get("numHands");
        $blackjack = $session->get("blackjack");
        $playerName = $session->get("playerName");
        $balance = $session->get("balance");
        $bet = $session->get("bet");
        $activeGame = $session->get("activeGame");

        if (!$playerName) {
            $playerName = $request->query->get('playerName');
            $session->set("playerName", $playerName);
        }

        if (!$balance) {
            $balance = $request->query->get('balance');
            $session->set("balance", $balance);
        }

        $playerHands = [];
        $dealerHand = [];
        $playerHandsValue = [];
        $dealerHandValue = 0;
        $results = [];
        $playersStood = [];

        if ($activeGame) {
            if (!$blackjack) {
                $blackjack = new Blackjack($numHands);
                $session->set("blackjack", $blackjack);
                $blackjack->startGame();
            } else {
                $blackjack = $session->get("blackjack");
            }

            $playerHands = $blackjack->getPlayerHands();
            $dealerHand = $blackjack->getDealerHand();

            $playerHandsValue = [];
            $dealerHandValue = $blackjack->calculateHandValue($dealerHand);

            foreach ($playerHands as $playerHand) {
                $playerHandsValue[] = $blackjack->calculateHandValue($playerHand);
            }

            if ($blackjack->allPlayersStood()) {
                $blackjack->dealerPlay();
                $dealerHand = $blackjack->getDealerHand();
                $dealerHandValue = $blackjack->calculateHandValue($dealerHand);

                $results = $blackjack->determineWinners();

                foreach ($results as $result) {
                    if ($result === 'win') {
                        $balance += $bet * 2;
                    } elseif ($result === 'push') {
                        $balance += $bet;
                    }
                }
                $session->set("balance", $balance);
            }

            $playersStood = $blackjack->getPlayersStood();
        }

        return $this->render('proj/game.html.twig', [
            'playerHands' => $playerHands,
            'dealerHand' => $dealerHand,
            'playerHandsValue' => $playerHandsValue,
            'dealerHandValue' => $dealerHandValue,
            'numHands' => $numHands,
            'results' => $results,
            'playersStood' => $playersStood,
            'playerName' => $playerName,
            'balance' => $balance,
            'bet' => $bet,
            'activeGame' => $activeGame,
        ]);
    }



    #[Route('/proj/game/post', name: "proj/game/post", methods: ["POST"])]
    public function projGamePost(Request $request, SessionInterface $session): Response
    {
        $bet = (int)$request->request->get('bet');
        $numHands = (int)$request->request->get('numHands');
        $balance = (int)$session->get("balance");

        $totalBet = $bet * $numHands;
        $updatedBalance = $balance - $totalBet;

        $session->set("bet", $bet);
        $session->set("numHands", $numHands);
        $session->set("balance", $updatedBalance);
        $session->set("activeGame", true);

        return $this->redirectToRoute('proj/game');
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

    #[Route('/proj/play/again', name: "proj/play/again")]
    public function sessionDeleteGame(SessionInterface $session): Response
    {
        $numHands = $session->get("numHands");
        $playerName = $session->get("playerName");
        $balance = $session->get("balance");

        $session->clear();

        $session->set("numHands", $numHands);
        $session->set("playerName", $playerName);
        $session->set("balance", $balance);
        return $this->redirectToRoute('proj/game');
    }
}
