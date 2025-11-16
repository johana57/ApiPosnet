<?php
declare(strict_types=1);
require_once __DIR__ . '/../Models/Client.php';
require_once __DIR__ . '/../Models/Card.php';

final class CardService {

    public function __construct(
        private CardRepository $cardRepository) {
    }

    public function registerCard(array $data): Card {

        $brand = strtoupper($data["brand"]);
        if (!in_array($brand, ["VISA", "AMEX"])) {
            throw new ValidationException("Disculpe, solo trabajamos con tarjetas VISA o AMEX.");
        }

        if (!preg_match("/^[0-9]{8}$/", $data["number"])) {
            throw new ValidationException("El número de la tarjeta debe tener 8 dígitos.");
        }

        $client = new Client(
            $data["dni"],
            $data["first_name"],
            $data["last_name"]
        );

        $card = new Card(
            $brand,
            $data["bank"],
            $data["number"],
            $data["limit"],
            $client
        );

        $this->cardRepository->save($card);
       
        return $card;
    }
}
