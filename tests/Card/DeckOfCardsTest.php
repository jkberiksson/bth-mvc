<?php

namespace App\Tests\Card;

use App\Card\Card;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

class DeckOfCardsTest extends TestCase
{
    /** @var DeckOfCards */
    private $deck;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deck = new DeckOfCards();
    }

    /**
     * Test the constructor and getCards method.
     */
    public function testConstructorAndGetCards(): void
    {
        $cards = $this->deck->getCards();

        $this->assertCount(52, $cards);

        foreach ($cards as $card) {
            $this->assertInstanceOf(Card::class, $card);
        }
    }

    /**
     * Test the shuffleCards method.
     */
    public function testShuffleCards(): void
    {
        $originalOrder = $this->deck->getCards();
        $this->deck->shuffleCards();
        $shuffledOrder = $this->deck->getCards();

        $this->assertNotEquals($originalOrder, $shuffledOrder);
        $this->assertCount(52, $shuffledOrder);
    }

    /**
     * Test the drawCard method.
     */
    public function testDrawCard(): void
    {
        $initialCount = $this->deck->cardsLeftInDeck();

        $drawnCard = $this->deck->drawCard();

        $this->assertInstanceOf(Card::class, $drawnCard);
        $this->assertEquals($initialCount - 1, $this->deck->cardsLeftInDeck());
    }

    /**
     * Test the cardsLeftInDeck method.
     */
    public function testCardsLeftInDeck(): void
    {
        $this->assertEquals(52, $this->deck->cardsLeftInDeck());

        $this->deck->drawCard();
        $this->deck->drawCard();
        $this->assertEquals(50, $this->deck->cardsLeftInDeck());
    }

    /**
     * Test the addCard method.
     */
    public function testAddCard(): void
    {
        $initialCount = $this->deck->cardsLeftInDeck();

        $this->deck->addCard(new Card('2', 'Hearts'));

        $this->assertEquals($initialCount + 1, $this->deck->cardsLeftInDeck());
    }
}
