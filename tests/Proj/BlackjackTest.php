<?php

use PHPUnit\Framework\TestCase;
use App\Proj\Blackjack;
use App\Proj\Deck;
use App\Proj\Card;

class BlackjackTest extends TestCase
{
    public function testConstructor()
    {
        $numHands = 2;
        $blackjack = new Blackjack($numHands);

        $this->assertEquals($numHands, count($blackjack->getPlayerHands()));
        $this->assertEquals(array_fill(0, $numHands, false), $blackjack->getPlayersStood());
    }

    public function testStartGame()
    {
        $blackjack = new Blackjack(2);
        $blackjack->startGame();

        $playerHand = [
            new Card('K', 'Hearts'),
            new Card('A', 'Diamonds')
        ];

        $playerHandValue = $blackjack->calculateHandValue($playerHand);

        $this->assertEquals(21, $playerHandValue);

        $this->assertCount(2, $blackjack->getPlayerHands());
        $this->assertCount(2, $blackjack->getPlayerHands()[0]);
        $this->assertCount(2, $blackjack->getPlayerHands()[1]);
        $this->assertCount(1, $blackjack->getDealerHand());
    }

    public function testHitPlayer()
    {
        $blackjack = new Blackjack(1);
        $blackjack->startGame();
        $initialHand = $blackjack->getPlayerHands()[0];
        $blackjack->hitPlayer(0);
        $newHand = $blackjack->getPlayerHands()[0];

        $this->assertNotEquals($initialHand, $newHand);
    }

    public function testHitDealer()
    {
        $blackjack = new Blackjack(1);
        $blackjack->startGame();
        $initialHand = $blackjack->getDealerHand();
        $blackjack->hitDealer();
        $newHand = $blackjack->getDealerHand();

        $this->assertNotEquals($initialHand, $newHand);
    }

    public function testAllPlayersStood()
    {
        $blackjack = new Blackjack(2);
        $this->assertFalse($blackjack->allPlayersStood());
        $blackjack->standPlayer(0);
        $this->assertFalse($blackjack->allPlayersStood());
        $blackjack->standPlayer(1);
        $this->assertTrue($blackjack->allPlayersStood());
    }

    public function testDealerPlay()
    {
        $blackjack = new Blackjack(1);
        $blackjack->startGame();
        $initialHandValue = $blackjack->calculateHandValue($blackjack->getDealerHand());
        $blackjack->dealerPlay();
        $newHandValue = $blackjack->calculateHandValue($blackjack->getDealerHand());

        $this->assertGreaterThanOrEqual(17, $newHandValue);
        $this->assertGreaterThan($initialHandValue, $newHandValue);
    }

    public function testDetermineWinners_playerWin()
    {
        $blackjack = new Blackjack(1);
        $blackjack->startGame();

        $playerHand = [
            new Card('K', 'Hearts'),
            new Card('8', 'Diamonds')
        ];

        $dealerHand = [
            new Card('5', 'Clubs'),
            new Card('4', 'Spades')
        ];

        $blackjack->setPlayerHand(0, $playerHand);
        $blackjack->setDealerHand($dealerHand);

        $results = $blackjack->determineWinners();

        $this->assertEquals('win', $results[0]);
    }

    public function testDetermineWinners_dealerBust()
    {
        $blackjack = new Blackjack(1);
        $blackjack->startGame();

        $playerHand = [
            new Card('K', 'Hearts'),
            new Card('8', 'Diamonds')
        ];

        $dealerHand = [
            new Card('5', 'Clubs'),
            new Card('7', 'Spades'),
            new Card('10', 'Spades')
        ];

        $blackjack->setPlayerHand(0, $playerHand);
        $blackjack->setDealerHand($dealerHand);

        $results = $blackjack->determineWinners();

        $this->assertEquals('win', $results[0]);
    }

    public function testDetermineWinners_playerLoss()
    {
        $blackjack = new Blackjack(1);
        $blackjack->startGame();

        $playerHand = [
            new Card('5', 'Hearts'),
            new Card('8', 'Diamonds')
        ];

        $dealerHand = [
            new Card('K', 'Clubs'),
            new Card('Q', 'Spades')
        ];

        $blackjack->setPlayerHand(0, $playerHand);
        $blackjack->setDealerHand($dealerHand);

        $results = $blackjack->determineWinners();

        $this->assertEquals('loss', $results[0]);
    }

    public function testDetermineWinners_playerLossOver21()
    {
        $blackjack = new Blackjack(1);
        $blackjack->startGame();

        $playerHand = [
            new Card('5', 'Hearts'),
            new Card('8', 'Diamonds'),
            new Card('10', 'Diamonds')
        ];

        $dealerHand = [
            new Card('K', 'Clubs'),
            new Card('Q', 'Spades')
        ];

        $blackjack->setPlayerHand(0, $playerHand);
        $blackjack->setDealerHand($dealerHand);

        $results = $blackjack->determineWinners();

        $this->assertEquals('loss', $results[0]);
    }

    public function testDetermineWinners_push()
    {
        $blackjack = new Blackjack(1);
        $blackjack->startGame();

        $playerHand = [
            new Card('K', 'Hearts'),
            new Card('Q', 'Diamonds')
        ];

        $dealerHand = [
            new Card('K', 'Clubs'),
            new Card('Q', 'Spades')
        ];

        $blackjack->setPlayerHand(0, $playerHand);
        $blackjack->setDealerHand($dealerHand);

        $results = $blackjack->determineWinners();

        $this->assertEquals('push', $results[0]);
    }
}
