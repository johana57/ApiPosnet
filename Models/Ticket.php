<?php
declare(strict_types=1);

final class Ticket {

    public function __construct(
        public string $clientName,
        public float $totalAmount,
        public float $installmentAmount) {
       
    }
}
