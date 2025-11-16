<?php
declare(strict_types=1);

class Card {

    public function __construct(
       private string $brand,
       private string $bank,
       private string $number,
       private float  $limit,
       private Client $client
    ) {
        $this->brand  = strtoupper($brand);
    }

    public function getBrand(): string
    { 
        return $this->brand;
    }

    public function getBank(): string
    { 
        return $this->bank;
    }

    public function getNumber(): string
    { 
        return $this->number;
    }

    public function getLimit(): float { 
        return $this->limit;
    }

    public function getClient(): Client 
    { 
        return $this->client;
    }

    public function decreaseLimit(float $amount): void {
        $this->limit -= $amount;
    }
}
