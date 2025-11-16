<?php
declare(strict_types=1);

final class CardRepository {

    public function save(Card $card): void {
        $_SESSION['cards'][$card->getNumber()] = $card;
    }

    public function findByNumber(string $number): ?Card {
        return $_SESSION['cards'][$number] ?? null;
    }
}

