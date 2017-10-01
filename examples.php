<?php
    require 'vendor/autoload.php';

    try {
        $sender = new Lapshev\SmsAssistant\Sender('CMC', 'username', 'password');

        echo $sender->getBalance() . PHP_EOL;                                 // will output balance
        echo $sender->sendMessage('+375297777777', 'Message test') . PHP_EOL; // will output message id
        echo $sender->getMessageStatus(9538851107);                           // will output message status
    }
    catch(\Lapshev\SmsAssistant\Exception $e) {
        die($e->getMessage());
    }