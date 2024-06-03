<?php

namespace App\Proj;

use App\Proj\Card;

/**
 * Represents a deck of playing cards.
 */
class Deck
{
    /** @var Card[] The cards in the deck. */
    private array $cards = [];

    /**
     * Constructs a new Deck instance.
     */
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

    /**
     * Shuffles the cards in the deck.
     */
    public function shuffleCards(): void
    {
        shuffle($this->cards);
    }

    /**
     * Draws a card from the deck.
     *
     * @return Card|null The drawn card, or null if the deck is empty.
     */
    public function drawCard(): ?Card
    {
        return array_pop($this->cards);
    }
}
