<?php

namespace App\Proj;

use App\Proj\Deck;
use App\Proj\Card;

/**
 * Class Blackjack represents a implementation of the Blackjack game.
 */
class Blackjack
{
    /** @var Deck The deck of cards used in the game. */
    private Deck $deck;

    /** @var (Card|null)[][] The hands of the player. */
    private array $playerHands = [];

    /** @var (Card|null)[] The dealer's hand. */
    private array $dealerHand = [];

    /** @var bool[] A boolean array to track if players have stood. */
    private array $playersStood;

    /**
     * Constructs a new Blackjack instance.
     *
     * @param int $numHands The number of hands in the game.
     */
    public function __construct(int $numHands)
    {
        $this->deck = new Deck();
        $this->playersStood = array_fill(0, $numHands, false);

        // Initialize hands for all players
        for ($i = 0; $i < $numHands; $i++) {
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
                if ($handValue === 21) {
                    $this->standPlayer($playerIndex);
                }
            }
        }
        $this->dealerHand[] = $this->deck->drawCard();
    }

    /**
     * Retrieves the hands of all players.
     *
     * @return (Card|null)[][] The hands of all players.
     */
    public function getPlayerHands(): array
    {
        return $this->playerHands;
    }

    /**
     * Retrieves the dealer's hand.
     *
     * @return Card[]|null[] The dealer's hand, or null if the hand is empty.
     */
    public function getDealerHand(): ?array
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
        $card = $this->deck->drawCard();
        if ($card instanceof Card) {
            $this->playerHands[$playerIndex][] = $card;

            $handValue = $this->calculateHandValue($this->playerHands[$playerIndex]);
            if ($handValue > 21 || $handValue === 21) {
                $this->standPlayer($playerIndex);
            }
        }
    }

    /**
     * Adds a card to the dealer's hand.
     */
    public function hitDealer(): void
    {
        $card = $this->deck->drawCard();
        if ($card instanceof Card) {
            $this->dealerHand[] = $card;
        }
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
     * @param (Card|null)[] $hand The hand to calculate the value for.
     *
     * @return int The current value of the hand.
     */
    public function calculateHandValue(array $hand): int
    {
        $value = 0;
        $aceCount = 0;

        foreach ($hand as $card) {
            if ($card instanceof Card) {
                $rank = $card->getRank();
                if ($rank === 'A') {
                    $aceCount++;
                    $value += 11;
                } elseif (in_array($rank, ['J', 'Q', 'K'], true)) {
                    $value += 10;
                } else {
                    $value += intval($rank);
                }
            }
        }

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
        return !in_array(false, $this->playersStood, true);
    }

    /**
     * Retrieves an array indicating whether each player has stood.
     *
     * @return bool[] An array representing the standing status of each player.
     *               Each element is a boolean value indicating whether the corresponding player has stood.
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
     * @return string[] The result of the game for each player.
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

    /**
     * Sets the hand of a specific player.
     *
     * @param int $playerIndex The index of the player.
     * @param Card[] $hand The hand to set for the player.
     */
    public function setPlayerHand(int $playerIndex, array $hand): void
    {
        $this->playerHands[$playerIndex] = $hand;
    }

    /**
     * Sets the dealer's hand.
     *
     * @param Card[] $hand The hand to set for the dealer.
     */
    public function setDealerHand(array $hand): void
    {
        $this->dealerHand = $hand;
    }
}
