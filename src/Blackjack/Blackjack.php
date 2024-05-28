<?php

namespace App\Blackjack;

use App\Card\DeckOfCards;
use App\Card\Card;

/**
 * Class Blackjack represents a simple implementation of the Blackjack game.
 */
class Blackjack
{
    /** @var DeckOfCards The deck of cards used in the game. */
    private $deck;

    /** @var Card[] The player's hand. */
    /** @phpstan-ignore-next-line */
    private $playerHand;

    /** @var Card[] The dealer's hand. */
    /** @phpstan-ignore-next-line */
    private $dealerHand;

    /**
     * Constructs a new Blackjack instance.
     */
    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->playerHand = [];
        $this->dealerHand = [];
    }

    /**
     * Starts the game by shuffling the deck and dealing initial cards.
     */
    public function startGame(): void
    {
        $this->deck->shuffleCards();
        $this->dealCardToPlayer();
        $this->dealCardToDealer();
        $this->dealCardToPlayer();
    }

    /**
     * Retrieves the player's hand.
     *
     * @return Card[] The player's hand.
     */
    public function getPlayerHand(): array
    {
        return $this->playerHand;
    }

    /**
     * Retrieves the dealer's hand.
     *
     * @return Card[] The dealer's hand.
     */
    public function getDealerHand(): array
    {
        return $this->dealerHand;
    }

    /**
     * Adds a card to the player's hand.
     */
    public function hitPlayer(): void
    {
        $this->dealCardToPlayer();
    }

    /**
     * Adds a card to the dealer's hand.
     */
    public function hitDealer(): void
    {
        $this->dealCardToDealer();
    }

    /**
     * Deals a card to the player's hand.
     */
    private function dealCardToPlayer(): void
    {
        $this->playerHand[] = $this->deck->drawCard();
    }

    /**
     * Deals a card to the dealer's hand.
     */
    private function dealCardToDealer(): void
    {
        $this->dealerHand[] = $this->deck->drawCard();
    }

    /**
     * Calculates the total value of a hand.
     *
     * @param Card[] $hand The hand to calculate the value for.
     *
     * @return int The total value of the hand.
     */
    public function calculateHandValue(array $hand): int
    {
        $value = 0;
        $aceCount = 0;

        foreach ($hand as $card) {
            $rank = $card->getRank();
            if ($rank === 'A') {
                $aceCount++;
            }
            $value += $this->getCardValue($rank);
        }

        while ($aceCount > 0 && $value > 21) {
            $value -= 10;
            $aceCount--;
        }

        return $value;
    }

    /**
     * Retrieves the value of a card.
     *
     * @param string $rank The rank of the card.
     *
     * @return int The value of the card.
     */
    private function getCardValue(string $rank): int
    {
        if (in_array($rank, ['J', 'Q', 'K'])) {
            return 10;
        }

        if ($rank === 'A') {
            return 11;
        }
        return intval($rank);
    }

    /**
     * Determines the winner of the game based on hand values.
     *
     * @param int $playerHandValue The value of the player's hand.
     * @param int $dealerHandValue The value of the dealer's hand.
     *
     * @return string The result of the game.
     */
    public function calculateWinner(int $playerHandValue, int $dealerHandValue): string
    {
        if ($playerHandValue > 21) {
            return "Du har blivit tjock. Dealern vann.";
        }

        if ($dealerHandValue > 21) {
            return "Dealern har blivit tjock. Du vann.";
        }

        if ($playerHandValue == $dealerHandValue) {
            return "Det blev oavgjort. Ingen vann.";
        }

        if ($playerHandValue > $dealerHandValue) {
            return "Du har högre poäng än dealern. Du vann.";
        }

        return "Dealern har högre poäng än dig. Dealern vann.";
    }
}
