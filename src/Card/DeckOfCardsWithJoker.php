<?php

namespace App\Card;

class DeckOfCardsWithJoker extends DeckOfCards
{
    public function __construct()
    {
        parent::__construct();

        $this->addCard(new Card('Joker', 'Black'));
        $this->addCard(new Card('Joker', 'Red'));
    }
}
