<?php

namespace App\Tests\Card;

use App\Card\Card;
use App\Card\DeckOfCardsWithJoker;
use PHPUnit\Framework\TestCase;

class DeckOfCardsWithJokerTest extends TestCase
{
    /** @var DeckOfCardsWithJoker */
    private $deckWithJoker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deckWithJoker = new DeckOfCardsWithJoker();
    }

    /**
     * Test the constructor and getCards method.
     */
    public function testConstructorAndGetCards(): void
    {
        $cards = $this->deckWithJoker->getCards();

        $this->assertCount(54, $cards);

        foreach ($cards as $card) {
            $this->assertInstanceOf(Card::class, $card);
        }
    }

    /**
     * Test the shuffleCards method.
     */
    public function testShuffleCards(): void
    {
        $originalOrder = $this->deckWithJoker->getCards();
        $this->deckWithJoker->shuffleCards();
        $shuffledOrder = $this->deckWithJoker->getCards();

        $this->assertNotEquals($originalOrder, $shuffledOrder);
        $this->assertCount(54, $shuffledOrder);
    }

    /**
     * Test the drawCard method.
     */
    public function testDrawCard(): void
    {
        $initialCount = $this->deckWithJoker->cardsLeftInDeck();

        $drawnCard = $this->deckWithJoker->drawCard();

        $this->assertInstanceOf(Card::class, $drawnCard);
        $this->assertEquals($initialCount - 1, $this->deckWithJoker->cardsLeftInDeck());
    }

    /**
     * Test the cardsLeftInDeck method.
     */
    public function testCardsLeftInDeck(): void
    {
        $this->assertEquals(54, $this->deckWithJoker->cardsLeftInDeck());

        $this->deckWithJoker->drawCard();
        $this->deckWithJoker->drawCard();
        $this->assertEquals(52, $this->deckWithJoker->cardsLeftInDeck());
    }
}
