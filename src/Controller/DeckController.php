<?php

namespace App\Controller;

use App\Card\DeckOfCardsWithJoker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeckController extends AbstractController
{
    #[Route('/api/deck', name: "api/deck", methods: ["GET"])]
    public function jsonDeck(): Response
    {
        $deckOfCards = new DeckOfCardsWithJoker();
        $deckOfCards = $deckOfCards->getCards();

        $cards = [];
        $cardCount = count($deckOfCards);

        for ($i = 0; $i < $cardCount; $i++) {
            array_push($cards, $deckOfCards[$i]->getAsString());
        };



        $data = [
            "deckOfCards" => $cards
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/api/deck/shuffle', name: "api/deck/shuffle", methods: ["POST"])]
    public function jsonDeckShuffle(SessionInterface $session): Response
    {
        $deckOfCards = new DeckOfCardsWithJoker();
        $deckOfCards->shuffleCards();

        $session->set('shuffled_deck', $deckOfCards);

        $deckOfCards = $deckOfCards->getCards();


        $cards = [];
        $cardCount = count($deckOfCards);

        for ($i = 0; $i < $cardCount; $i++) {
            array_push($cards, $deckOfCards[$i]->getAsString());
        };

        $data = [
            "deckOfCards" => $cards
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/api/deck/draw/{num<\d+>}', name: "api/deck/draw/number", methods: ["POST"])]
    public function jsonDeckDrawNumber(SessionInterface $session, int $num): Response
    {
        /** @var DeckOfCardsWithJoker|null $deckOfCards */
        $deckOfCards = $session->get("deckOfCards");

        if (!$deckOfCards instanceof DeckOfCardsWithJoker) {
            $deckOfCards = new DeckOfCardsWithJoker();
            $session->set("deckOfCards", $deckOfCards);
        }

        $drawnCards = [];

        for ($i = 1; $i <= $num; $i++) {
            $drawnCard = $deckOfCards->drawCard();
            if ($drawnCard) {
                $drawnCards[] = $drawnCard->getAsString();
            }
        }

        $cardsLeftInDeck = $deckOfCards->cardsLeftInDeck();

        $data = [
            "drawnCards" => $drawnCards,
            "cardsLeftInDeck" => $cardsLeftInDeck
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
