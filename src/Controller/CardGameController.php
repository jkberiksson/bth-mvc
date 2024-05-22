<?php

namespace App\Controller;

use App\Card\DeckOfCardsWithJoker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    #[Route('/session', name: "session")]
    public function landingPageSession(SessionInterface $session): Response
    {
        $deckOfCards = $session->get("deckOfCards");

        $data = [
            "deckOfCards" => $deckOfCards
        ];

        return $this->render('session.html.twig', $data);
    }

    #[Route('/session/delete', name: "session/delete", methods: ["POST"])]
    public function sessionDelete(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash('success', 'Session has been cleared.');
        return $this->redirectToRoute('session');
    }

    #[Route('/card', name: "card")]
    public function card(): Response
    {
        return $this->render('card/card.html.twig');
    }

    #[Route('/card/deck', name: "card/deck")]
    public function cardDeck(SessionInterface $session): Response
    {
        $deckOfCards = $session->get("deckOfCards");

        if (!$deckOfCards) {
            $deckOfCards = new DeckOfCardsWithJoker();
            $session->set("deckOfCards", $deckOfCards);
        }

        $data = [
            "deckOfCards" => $deckOfCards
        ];

        return $this->render('card/card-deck.html.twig', $data);
    }

    #[Route('/card/deck/shuffle', name: "card/deck/shuffle")]
    public function cardDeckShuffle(): Response
    {
        $deckOfCards = new DeckOfCardsWithJoker();
        $deckOfCards->shuffleCards();

        $data = [
            "deckOfCards" => $deckOfCards
        ];

        return $this->render('card/card-deck-shuffle.html.twig', $data);
    }

    #[Route('/card/deck/draw', name: "card/deck/draw")]
    public function cardDeckDraw(SessionInterface $session): Response
    {
        $deckOfCards = $session->get("deckOfCards");

        if (!$deckOfCards) {
            $deckOfCards = new DeckOfCardsWithJoker();
            $session->set("deckOfCards", $deckOfCards);
        }

        $drawnCard = $deckOfCards->drawCard();
        $cardsLeftInDeck = $deckOfCards->cardsLeftInDeck();

        $data = [
            "drawnCard" => $drawnCard,
            "cardsLeftInDeck" => $cardsLeftInDeck
        ];

        return $this->render('card/card-deck-draw.html.twig', $data);
    }

    #[Route('/card/deck/draw/number/{num<\d+>}', name: "card/deck/draw/number")]
    public function cardDeckDrawNumber(SessionInterface $session, int $num): Response
    {
        $deckOfCards = $session->get("deckOfCards");

        if (!$deckOfCards) {
            $deckOfCards = new DeckOfCardsWithJoker();
            $session->set("deckOfCards", $deckOfCards);
        }

        $drawnCards = [];

        for ($i = 1; $i <= $num; $i++) {
            $drawnCard = $deckOfCards->drawCard();
            $drawnCards[] = $drawnCard;
        };

        $cardsLeftInDeck = $deckOfCards->cardsLeftInDeck();

        $data = [
            "drawnCards" => $drawnCards,
            "cardsLeftInDeck" => $cardsLeftInDeck
        ];

        return $this->render('card/card-deck-draw-number.html.twig', $data);
    }

    #[Route('/card/deck/draw/number', name: "card/deck/draw/number/post", methods: ['POST'])]
    public function cardDeckDrawNumberPost(Request $request): Response
    {
        $amountOfDrawnCards = $request->request->get('num');

        return $this->redirectToRoute('card/deck/draw/number', ['num' => $amountOfDrawnCards]);
    }
}
