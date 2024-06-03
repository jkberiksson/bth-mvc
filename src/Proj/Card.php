<?php

namespace App\Proj;

class Card
{
    /**
     * @var string The rank of the card (e.g., '2', '3', 'A', 'K').
     */
    private string $rank;

    /**
     * @var string The suit of the card (e.g., 'Hearts', 'Diamonds', 'Clubs', 'Spades').
     */
    private string $suit;

    /**
     * Constructs a new Card instance.
     *
     * @param string $rank The rank of the card.
     * @param string $suit The suit of the card.
     */
    public function __construct(string $rank, string $suit)
    {
        $this->rank = $rank;
        $this->suit = $suit;
    }

    /**
     * Gets the rank of the card.
     *
     * @return string The rank of the card.
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * Gets the suit of the card.
     *
     * @return string The suit of the card.
     */
    public function getSuit(): string
    {
        return $this->suit;
    }
}
