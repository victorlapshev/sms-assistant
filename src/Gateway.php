<?php

    namespace Lapshev\SmsAssistant;

    use GuzzleHttp\Client;

    class Gateway
    {
        const API_POINT = 'https://userarea.sms-assistent.by/api/v1';

        const RES_ERRORS = __DIR__ . '/../resources/errors.json';

        const DEFAULT_PARAMS = [
            'debug' => false, 'debug_filename' => '', 'timeout' => 2.0,
        ];

        /** @var string */
        protected $login;

        /** @var string */
        protected $password;

        /** @var array */
        protected $params = [];

        /**
         * Usual get request with query string
         *
         * @param string $path
         * @param array $params
         *
         * @return string Request
         * @throws Exception
         */
        protected function plainRequest($path, $params = []) {
            $params = array_merge($params, $this->getCredentials());

            if($this->getParam('debug')) {

                $params['_path'] = $path;
                $params['_time'] = date('d-m-Y H:i:s');

                file_put_contents($this->getParam('debug_filename'), print_r($params, true), FILE_APPEND);

                return 'debug_mode_response_string';
            }

            $client = new Client(['timeout' => $this->getParam('timeout')]);

            $response = $client->request('GET', sprintf("%s/%s/plain", self::API_POINT, $path), [
                'query' => $params,
            ]);

            if($response->getStatusCode() !== 200) {
                throw new Exception('Response code:' . $response->getStatusCode() . ' body:' .
                    $response->getBody()->getContents());
            }

            $responseString = $response->getBody()->getContents();

            if(floatval($responseString) < 0) {
                throw new Exception($this->getErrorText($responseString));
            }

            return $responseString;
        }

        /**
         * not implemented yet
         *
         * @param $params
         */
        protected function jsonRequest($params) {
            $params = array_merge($params, $this->getCredentials());
        }

        /**
         * @return array
         */
        private function getCredentials() {
            return [
                'user' => $this->login, 'password' => $this->password,
            ];
        }

        /**
         * Get readable message by error code
         *
         * @param string $code
         *
         * @return string Error message text
         * @throws Exception
         */
        private function getErrorText($code) {

            if(!is_readable(self::RES_ERRORS)) {
                throw new Exception('Errors file not found: ' . self::RES_ERRORS);
            }

            $errorsMap = json_decode(file_get_contents(self::RES_ERRORS), true);

            if(json_last_error()) {
                throw new Exception('Corrupted json in: ' . self::RES_ERRORS);
            }

            if(!isset($errorsMap[abs($code)])) {
                throw new Exception('Unknown error code:' . $code);
            }

            return $errorsMap[abs($code)];
        }

        private function getParam($name) {
            return isset($this->params[$name]) ? $this->params[$name]
                : (isset(self::DEFAULT_PARAMS[$name]) ? self::DEFAULT_PARAMS[$name] : null);
        }
    }