<?php

namespace App\Tests\Card;

use App\Card\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    /**
     * Test the constructor and getter methods.
     */
    public function testConstructorAndGetters(): void
    {
        $rank = 'A';
        $suit = 'Hearts';

        $card = new Card($rank, $suit);

        $this->assertEquals($rank, $card->getRank());
        $this->assertEquals($suit, $card->getSuit());
    }

    /**
     * Test the getAsString method.
     */
    public function testGetAsString(): void
    {
        $rank = 'Q';
        $suit = 'Diamonds';

        $card = new Card($rank, $suit);

        $expectedString = "[{$rank} of {$suit}]";
        $this->assertEquals($expectedString, $card->getAsString());
    }
}
