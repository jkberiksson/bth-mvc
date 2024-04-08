<?php

namespace App\Card;

class DeckOfCards
{
    private $cards = [];

    public function __construct()
    {
        $ranks = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
        $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];

        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $this->cards[] = new Card($rank, $suit);
            }
        }
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function shuffleCards(): void
    {
        shuffle($this->cards);
    }

    public function drawCard(): ?Card
    {
        return array_pop($this->cards);
    }

    public function cardsLeftInDeck(): int
    {
        return count($this->cards);
    }

    public function addCard(Card $card)
    {
        $this->cards[] = $card;
    }
}
