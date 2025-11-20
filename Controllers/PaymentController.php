<?php
declare(strict_types=1);

require_once __DIR__ . '/../Services/CardService.php';
require_once __DIR__ . '/../Services/PosnetService.php';
require_once __DIR__ . '/../Exceptions/ValidationException.php';
require_once __DIR__ . '/../Exceptions/PaymentException.php';

final class PaymentController
{
    private CardService $cardService;
    private PosnetService $posnetService;

    public function __construct(CardService $cardService, PosnetService $posnetService)
    {
        $this->cardService   = $cardService;
        $this->posnetService = $posnetService;
    }

    public function handleRequest(): void
    {
        $uri = strtok($_SERVER["REQUEST_URI"], '?');
        $method = $_SERVER["REQUEST_METHOD"];
        $data = json_decode(file_get_contents("php://input"), true) ?? [];

        try {
            $response = match ([$method, $uri]) {

                ['POST', '/card/register'] => $this->registerCard($data),

                ['POST', '/payment/do']    => $this->doPayment($data),

                default => throw new ValidationException("Ruta no encontrada", 404)
            };

            echo json_encode($response);

        } catch (ValidationException | PaymentException $e) {
            echo json_encode([
                "status"  => "error",
                "code"    => $e->getCode(),
                "message" => $e->getMessage()
            ]);

        } catch (Exception $e) {
            echo json_encode([
                "status"  => "error",
                "code"    => 500,
                "message" => $e->getMessage()
            ]);
        }
    }

    private function registerCard(array $data): array
    {
        $card = $this->cardService->registerCard($data);

        return [
            "status"     => "success",
            "numberCard" => $card->getNumber()
        ];
    }

    private function doPayment(array $data): array
    {
        $ticket = $this->posnetService->doPayment(
            $data["number"],
            $data["amount"],
            $data["installments"]
        );

        return [
            "status" => "success",
            "ticket" => [
                "client"       => $ticket->clientName,
                "total"        => $ticket->totalAmount,
                "installment"  => $ticket->installmentAmount,
                "newLimit"        => $ticket->limit
            ]
        ];
    }
}
