<?php
declare(strict_types=1);

final class PaymentException extends Exception {

    public function __construct(string $message, int $code = 422) {
        parent::__construct($message, $code);
    }
}
