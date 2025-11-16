<?php
declare(strict_types=1);
require_once __DIR__ . '/../Models/Ticket.php';

final class PosnetService {

    public function __construct(
        private CardRepository $cardRepository) {
    }

    public function doPayment(
        string $cardNumber,
        float $amount,
        int $installments): Ticket
    {
        if ($installments < 1 || $installments > 6) {
            throw new PaymentException("El límite de cuotas es 6. ");
        }

        $card = $this->cardRepository->findByNumber($cardNumber);
        if (!$card) {
            throw new PaymentException("La tarjeta no existe.");
        }

        $interestPercent = ($installments - 1) * 0.03;
        $total = $amount * (1 + $interestPercent);
        $perInstallment = $total / $installments;

        if ($total > $card->getLimit()) {
            throw new PaymentException("Límite de tarjeta insuficiente.");
        }

        $card->decreaseLimit($total);

        return new Ticket(
            $card->getClient()->getFullName(),
            round($total, 2),
            round($perInstallment, 2)
        );
    }
}
