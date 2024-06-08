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

    /** @var array A boolean array to track if players have stood. */
    private $playersStood;

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
        $this->playersStood = array_fill(0, $numPlayers, false);

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
            foreach ($this->playerHands as $playerIndex => &$hand) {
                $hand[] = $this->deck->drawCard();
                $handValue = $this->calculateHandValue($hand);
                if ($handValue == 21) {
                    $this->standPlayer($playerIndex);
                }
            }
        }
        $this->dealerHand[] = $this->deck->drawCard();
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

        $handValue = $this->calculateHandValue($this->playerHands[$playerIndex]);
        if ($handValue > 21 || $handValue == 21) {
            $this->standPlayer($playerIndex);
        }
    }

    /**
     * Adds a card to the dealer's hand.
     */
    public function hitDealer(): void
    {
        $this->dealerHand[] = $this->deck->drawCard();
    }

    /**
     * Sets the player's stand status to true.
     *
     * @param int $playerIndex The index of the player.
     */
    public function standPlayer(int $playerIndex): void
    {
        $this->playersStood[$playerIndex] = true;
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

    /**
     * Check if all players have stood.
     *
     * @return bool True if all players have stood, otherwise false.
     */
    public function allPlayersStood(): bool
    {
        return !in_array(false, $this->playersStood);
    }


    /**
     * Retrieves an array indicating whether each player has stood.
     *
     * @return array An array representing the standing status of each player.
     * Each element is a boolean value indicating whether the corresponding player has stood.
     */
    public function getPlayersStood(): array
    {
        return $this->playersStood;
    }


    /**
     * Dealer plays according to Blackjack rules.
     */
    public function dealerPlay(): void
    {
        while ($this->calculateHandValue($this->dealerHand) < 17) {
            $this->hitDealer();
        }
    }

    /**
     * Determines the result of the game.
     *
     * @return array The result of the game for each player.
     */
    public function determineWinners(): array
    {
        $results = [];
        $dealerHandValue = $this->calculateHandValue($this->dealerHand);

        foreach ($this->playerHands as $index => $playerHand) {
            $playerHandValue = $this->calculateHandValue($playerHand);
            if ($playerHandValue > 21) {
                $results[$index] = 'loss';
            } elseif ($dealerHandValue > 21) {
                $results[$index] = 'win';
            } elseif ($playerHandValue > $dealerHandValue) {
                $results[$index] = 'win';
            } elseif ($playerHandValue < $dealerHandValue) {
                $results[$index] = 'loss';
            } else {
                $results[$index] = 'push';
            }
        }

        return $results;
    }
}
