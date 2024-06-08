<?php

namespace App\Tests\Proj;

use PHPUnit\Framework\TestCase;
use App\Proj\Deck;
use App\Proj\Card;

class DeckTest extends TestCase
{
    public function testConstructor(): void
    {
        $deck = new Deck();
        $this->assertCount(52, $deck->getCards());
    }

    public function testShuffleCards(): void
    {
        $deck = new Deck();
        $originalOrder = $deck->getCards();
        $deck->shuffleCards();
        $this->assertNotEquals($originalOrder, $deck->getCards());
    }

    public function testDrawCard(): void
    {
        $deck = new Deck();
        $this->assertInstanceOf(Card::class, $deck->drawCard());
        for ($i = 0; $i < 51; $i++) {
            $deck->drawCard();
        }
        $this->assertNull($deck->drawCard());
    }
}
