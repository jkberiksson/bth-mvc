<?php

namespace App\Controller;

use App\Blackjack\Blackjack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlackjackController extends AbstractController
{
    #[Route('/api/game', name: "api/game", methods: ["GET"])]
    public function jsonGame(SessionInterface $session): Response
    {
        /** @var Blackjack|null $blackjack */
        $blackjack = $session->get("blackjack");

        $data = [];

        if ($blackjack instanceof Blackjack) {
            $data = [
                'Player hand' => $blackjack->calculateHandValue($blackjack->getPlayerHand()),
                'Dealer hand' => $blackjack->calculateHandValue($blackjack->getDealerHand()),
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
