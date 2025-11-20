<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../Repositories/CardRepository.php";
require_once __DIR__ . '/../Services/PosnetService.php';
require_once __DIR__ . '/..//Models/Card.php';
require_once __DIR__ . '/../Models/Client.php';
require_once __DIR__ . "/../Exceptions/PaymentException.php";

final class PosnetServiceTest extends TestCase
{
    public function testDoPaymentCalculatesTotalsCorrectly(): void
    {
        $cardRepositoryMock = $this->createMock(CardRepository::class);

        $client = new Client(
            "19099189",//dni
            "Johana",
            "Rivas"
        );

        $card = new Card(
            "AMEX", //marca
            "Santander", //banco
            "12345678", //Nro Tarjeta
            100000,
            $client
        );

        $cardRepositoryMock->method('findByNumber')->willReturn($card);

        $service = new PosnetService($cardRepositoryMock);

        $ticket = $service->doPayment(
            "12345678", //Nro tarjeta
            10000, //Monto
            3 //Cuotas
        );

        // Interés esperado: (3 - 1) * 3% = 6%
        // Total esperado: 10000 * 1.06 = 10600
        // Por cuota: 3533.33 aprox

        $this->assertEquals("Johana Rivas", $ticket->clientName);
        $this->assertEquals(10600, $ticket->totalAmount);
        $this->assertEquals(3533.33, $ticket->installmentAmount);
    }

    public function testExceptionWhenInstallmentsExceedLimit(): void
    {
        $cardRepositoryMock = $this->createMock(CardRepository::class);

        $client = new Client(
            "19099189",
            "Johana",
            "Rivas"
        );

        $card = new Card(
            "AMEX",
            "Santander",
            "12345678",
            100000,
            $client
        );

        $cardRepositoryMock
            ->method('findByNumber')
            ->willReturn($card);

        $service = new PosnetService($cardRepositoryMock);

        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage("El límite de cuotas es 6");

        $service->doPayment(
            "12345678",
            10000,
            7
        );
    }

}
