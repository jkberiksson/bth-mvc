<?php

namespace App\Tests;

use App\Blackjack\Blackjack;
use App\Card\Card;

use PHPUnit\Framework\TestCase;

class BlackjackTest extends TestCase
{
    /**
     * Test the startGame method.
     */
    public function testStartGame(): void
    {
        $blackjack = new Blackjack();

        $this->assertEmpty($blackjack->getPlayerHand());
        $this->assertEmpty($blackjack->getDealerHand());

        $blackjack->startGame();

        $this->assertNotEmpty($blackjack->getPlayerHand());
        $this->assertNotEmpty($blackjack->getDealerHand());
    }

    /**
     * Test the hitPlayer method.
     */
    public function testHitPlayer(): void
    {
        $blackjack = new Blackjack();
        $blackjack->startGame();

        $initialPlayerHand = $blackjack->getPlayerHand();

        $blackjack->hitPlayer();

        $this->assertCount(count($initialPlayerHand) + 1, $blackjack->getPlayerHand());
    }

    /**
     * Test the hitDealer method.
     */
    public function testHitDealer(): void
    {
        $blackjack = new Blackjack();
        $blackjack->startGame();

        $initialDealerHand = $blackjack->getDealerHand();

        $blackjack->hitDealer();

        $this->assertCount(count($initialDealerHand) + 1, $blackjack->getDealerHand());
    }

    /**
     * Test the calculateHandValue method.
     */
    public function testCalculateHandValue(): void
    {
        $blackjack = new Blackjack();

        $hand = [
            new Card('Q', 'Hearts'),
            new Card('K', 'Diamonds'),
            new Card('5', 'Spades'),
        ];

        $expectedValue = 25;

        $actualValue = $blackjack->calculateHandValue($hand);

        $this->assertEquals($expectedValue, $actualValue);
    }

    /**
     * Test the calculateWinner method.
     */
    public function testCalculateWinner(): void
    {
        $blackjack = new Blackjack();

        $result = $blackjack->calculateWinner(25, 18);
        $this->assertEquals("Du har blivit tjock. Dealern vann.", $result);

        $result = $blackjack->calculateWinner(18, 25);
        $this->assertEquals("Dealern har blivit tjock. Du vann.", $result);

        $result = $blackjack->calculateWinner(20, 20);
        $this->assertEquals("Det blev oavgjort. Ingen vann.", $result);

        $result = $blackjack->calculateWinner(21, 18);
        $this->assertEquals("Du har högre poäng än dealern. Du vann.", $result);

        $result = $blackjack->calculateWinner(18, 21);
        $this->assertEquals("Dealern har högre poäng än dig. Dealern vann.", $result);
    }

    /**
     * Test the getPlayerHand method.
     */
    public function testGetPlayerHand(): void
    {
        $blackjack = new Blackjack();

        $this->assertEmpty($blackjack->getPlayerHand());

        $blackjack->startGame();

        $this->assertNotEmpty($blackjack->getPlayerHand());
        $this->assertIsArray($blackjack->getPlayerHand());
    }

    /**
     * Test the getDealerHand method.
     */
    public function testGetDealerHand(): void
    {
        $blackjack = new Blackjack();

        $this->assertEmpty($blackjack->getDealerHand());

        $blackjack->startGame();

        $this->assertNotEmpty($blackjack->getDealerHand());
        $this->assertIsArray($blackjack->getDealerHand());
    }
}
