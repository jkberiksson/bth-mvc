<?php

namespace App\Blackjack;

use App\Card\DeckOfCards;

class Blackjack
{
    private $deck;
    private $playerHand;
    private $dealerHand;

    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->playerHand = [];
        $this->dealerHand = [];
    }

    public function startGame(): void
    {
        $this->deck->shuffleCards();
        $this->dealCardToPlayer();
        $this->dealCardToDealer();
        $this->dealCardToPlayer();
    }

    public function getPlayerHand(): array
    {
        return $this->playerHand;
    }

    public function getDealerHand(): array
    {
        return $this->dealerHand;
    }

    public function hitPlayer(): void
    {
        $this->dealCardToPlayer();
    }

    public function hitDealer(): void
    {
        $this->dealCardToDealer();
    }

    private function dealCardToPlayer(): void
    {
        $this->playerHand[] = $this->deck->drawCard();
    }

    private function dealCardToDealer(): void
    {
        $this->dealerHand[] = $this->deck->drawCard();
    }

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
