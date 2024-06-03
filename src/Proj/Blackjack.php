<?php

namespace App\Proj;

use App\Proj\Deck;

/**
 * Class Blackjack represents a simple implementation of the Blackjack game.
 */
class Blackjack
{
    /** @var Deck The deck of cards used in the game. */
    private $deck;

    /** @var array The hands of all players. */
    private $playerHands;

    /** @var array The dealer's hand. */
    private $dealerHand;

    /** @var int The number of players in the game. */
    private $numPlayers;

    /**
     * Constructs a new Blackjack instance.
     *
     * @param int $numPlayers The number of players in the game.
     */
    public function __construct(int $numPlayers)
    {
        $this->deck = new Deck();
        $this->playerHands = [];
        $this->dealerHand = [];
        $this->numPlayers = $numPlayers;

        // Initialize hands for all players
        for ($i = 0; $i < $numPlayers; $i++) {
            $this->playerHands[$i] = [];
        }
    }

    /**
     * Starts the game by shuffling the deck and dealing initial cards.
     */
    public function startGame(): void
    {
        $this->deck->shuffleCards();

        // Deal initial cards to players and dealer
        for ($i = 0; $i < 2; $i++) {
            foreach ($this->playerHands as &$hand) {
                $hand[] = $this->deck->drawCard();
            }
            $this->dealerHand[] = $this->deck->drawCard();
        }
    }

    /**
     * Retrieves the hands of all players.
     *
     * @return array The hands of all players.
     */
    public function getPlayerHands(): array
    {
        return $this->playerHands;
    }

    /**
     * Retrieves the dealer's hand.
     *
     * @return array The dealer's hand.
     */
    public function getDealerHand(): array
    {
        return $this->dealerHand;
    }

    /**
     * Adds a card to a player's hand.
     *
     * @param int $playerIndex The index of the player.
     */
    public function hitPlayer(int $playerIndex): void
    {
        $this->playerHands[$playerIndex][] = $this->deck->drawCard();
    }

    /**
     * Adds a card to the dealer's hand.
     */
    public function hitDealer(): void
    {
        $this->dealerHand[] = $this->deck->drawCard();
    }

    /**
     * Calculates the current value of a hand.
     *
     * @param array $hand The hand to calculate the value for.
     *
     * @return int The current value of the hand.
     */
    public function calculateHandValue(array $hand): int
    {
        $value = 0;
        $aceCount = 0;

        foreach ($hand as $card) {
            $rank = $card->getRank();
            if ($rank === 'A') {
                $aceCount++;
                // By default, consider ace as 11
                $value += 11;
            } elseif (in_array($rank, ['J', 'Q', 'K'])) {
                // Face cards have value 10
                $value += 10;
            } else {
                // Other cards have their numeric value
                $value += intval($rank);
            }
        }

        // Adjust value if there are aces and total value exceeds 21
        while ($aceCount > 0 && $value > 21) {
            $value -= 10;
            $aceCount--;
        }

        return $value;
    }
}
