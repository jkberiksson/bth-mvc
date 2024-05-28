<?php

namespace App\Controller;

use App\Card\DeckOfCardsWithJoker;
use App\Blackjack\Blackjack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\BookRepository;

class JsonApiController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function jsonRoutes(): Response
    {
        $data = [
            'routes' => [
                "api/qoute" => "Method: GET | Slumpar fram ett citat av tre möjliga och returnerar det tillsammans med dagens datum och timestamp.",
                "api/deck" => "Method: GET | Returnerar sorterad kortlek.",
                "api/deck/shuffle" => "Method: POST | Blandar kortleken och returnerar den samt spara i sessionen.",
                "api/deck/draw/:number" => "Method: POST | Drar antalet kort från kortleken och returnerar dom dragna korten samt antalet kort som finns kvar i kortleken.",
                "api/game" => "Method: GET | Visar upp aktuell ställning i blackjack.",
                "api/library/books" => "Method: GET | Visar upp samtliga böcker i biblioteket.",
                "api/library/book/:isbn" => "Method: GET | Visa upp en bok i biblioteket via dess ISBN.",
                "api/library/book/978045126584" => "",
            ],
        ];

        return $this->render('api.html.twig', $data);
    }

    #[Route("/api/qoute", name: "api/qoute", methods: ["GET"])]
    public function jsonQoute(): Response
    {
        $number = random_int(0, 2);

        $quotes = [
            "The only way to do great work is to love what you do. - Steve Jobs",
            "Success is not final, failure is not fatal: It is the courage to continue that counts. - Winston Churchill",
            "In the end, its not the years in your life that count. It's the life in your years. - Abraham Lincoln"
        ];

        $data = [
            'quote' => $quotes[$number],
            'date' => date('Y-m-d'),
            'timestamp' => time(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

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

    #[Route('/api/library/books', name: "library/books", methods: ["GET"])]
    public function jsonBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/api/library/book/{isbn}', name: "api_library_book_by_isbn", methods: ["GET"])]
    public function jsonBookByIsbn(BookRepository $bookRepository, string $isbn): Response
    {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            throw $this->createNotFoundException('No book found for ISBN ' . $isbn);
        }

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
