<?php
declare(strict_types=1);

class Client {

    public function __construct(
        private string $dni,
        private string $firstName,
        private string $lastName) {
    }

    public function getFullName(): string {
        return $this->firstName . " " . $this->lastName;
    }
}
