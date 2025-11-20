<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../Repositories/CardRepository.php";
require_once __DIR__ . "/../Exceptions/ValidationException.php";
require_once __DIR__ . "/../Services/CardService.php";

final class CardServiceTest extends TestCase
{
    public function testExceptionWhenCardNumberHasInvalidFormat(): void
    {
        $cardRepositoryMock = $this->createMock(CardRepository::class);
        $cardService = new CardService($cardRepositoryMock);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("El número de la tarjeta debe tener 8 dígitos.");

        $data = [
            "brand" => "VISA",
            "bank" => "Santander",
            "number" => "12345",
            "limit" => 100000,
            "dni" => "19099189",
            "first_name" => "Johana",
            "last_name" => "Rivas"
        ];

        $cardService->registerCard($data);
    }
}
