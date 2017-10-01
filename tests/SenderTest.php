<?php

    namespace Lapshev\SmsAssistant\Tests;

    use Lapshev\SmsAssistant\Sender;
    use PHPUnit\Framework\TestCase;

    final class SenderTest extends TestCase
    {
        const USER = 'username';
        const PASS = 'password';
        const SENDER = 'sender_name';

        /** @var Sender */
        private $sender;

        /** @var int */
        private $messageId;

        protected function setUp() {
            $this->sender = new Sender(self::SENDER, self::USER, self::PASS, [
                'debug'             => true,                        // tests not working in debug mode yet
                'debug_filename'    => __DIR__ . '/out/log.txt'
            ]);
        }

        public function testGetBalance() {
            $this->assertInternalType($this->sender->getBalance(), 'float');
        }

        public function testSendMessage() {
            $this->messageId = $this->sender->sendMessage('+375296666666', 'test message');
            $this->assertInternalType($this->messageId, 'int');
        }

        public function testGetMessageStatus() {
            $this->assertInternalType($this->sender->getMessageStatus($this->messageId), 'string');
        }
    }