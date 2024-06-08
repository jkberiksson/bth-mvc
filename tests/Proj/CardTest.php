<?php

namespace App\Tests\Proj;

use App\Proj\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    public function testGetRank(): void
    {
        $card = new Card('A', 'Hearts');
        $this->assertSame('A', $card->getRank());
    }

    public function testGetSuit(): void
    {
        $card = new Card('K', 'Spades');
        $this->assertSame('Spades', $card->getSuit());
    }
}
