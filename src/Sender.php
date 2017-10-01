<?php

    namespace Lapshev\SmsAssistant;

    /**
     * Class Sender
     *
     * @package Lapshev\SmsAssistant
     */
    class Sender extends Gateway
    {
        /** @var string */
        private $sender;

        /**
         * Sender constructor.
         *
         * @param string $sender
         * @param string $login
         * @param string $password
         * @param array $params
         */
        public function __construct($sender, $login, $password, $params = []) {
            $this->sender = $sender;
            $this->login = $login;
            $this->password = $password;
            $this->params = $params;
        }

        /**
         * Return account balance
         *
         * @return float account balance
         */
        public function getBalance() {
            return floatval($this->plainRequest('credits'));
        }

        /**
         * Try to send message and return message id
         *
         * @param string $recipient
         * @param string $message
         *
         * @return int Message id
         * @throws Exception
         */
        public function sendMessage($recipient, $message) {
            if(empty($recipient) || empty($message)) {
                throw new Exception('Both recipient and message can not be empty');
            }

            return intval($this->plainRequest('send_sms', [
                'recipient' => $recipient, 'message' => $message, 'sender' => $this->sender,
            ]));
        }

        /**
         * Get message status
         *
         * @param int $messageId Get message id
         *
         * @return string Message status
         * @throws Exception
         */
        public function getMessageStatus($messageId) {
            $messageId = intval($messageId);

            if(empty($messageId)) {
                throw new Exception('Empty message id');
            }

            return $this->plainRequest('statuses', [
                'id'    => $messageId
            ]);
        }
    }