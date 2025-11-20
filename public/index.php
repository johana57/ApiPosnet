<?php
require_once __DIR__ . "/../Repositories/CardRepository.php";
require_once __DIR__ . "/../Services/CardService.php";
require_once __DIR__ . "/../Services/PosnetService.php";
require_once __DIR__ . "/../Controllers/PaymentController.php";
require_once __DIR__ . "/../Exceptions/ValidationException.php";
require_once __DIR__ . "/../Exceptions/PaymentException.php";

session_start();

$cardRepository = new CardRepository();
$cardService    = new CardService($cardRepository);
$posnetService  = new PosnetService($cardRepository);

$controller = new PaymentController($cardService, $posnetService);
$controller->handleRequest();
