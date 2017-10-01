# sms-assistant #
Simple [sms-assistent](http://sms-assistent.by/) client implementation, based on [Guzzle Client](http://docs.guzzlephp.org/en/stable/).
### Usage ###
```php
<?php
    use Lapshev\SmsAssistant;
    
    try {
        $sender = new SmsAssistant\Sender('CMC', 'username', 'password');
        $messageId = $sender->sendMessage('+375297777777', 'Message test') . PHP_EOL;
        
    } catch( SmsAssistant\Exception $e ) {
        die($e->getMessage());
    }
```
#### Other features ###
 - `$sender->getBalance()` get account balance
 - `$sender->getMessageStatus($messageId);` get message status by id, returned by `$sender->sendMessage()`

#### Options ####
Passed as `third` parameter in `new SmsAssistant\Sender(,,,$params)`

- `timeout`           - http request timeout
- `debug`             - debug mode true or false
- `debug_filename`    - absolute path for output filename

#### Debug mode ####
If you don't want to actually send messages during integration, you can use debug mode as show bellow

```php
<?php
    use Lapshev\SmsAssistant;
    
    $sender = new SmsAssistant\Sender('s', 'u', 'p', [
        'debug'             => false,
        'debug_filename'    => __DIR__ . '/out/log.txt'
    ]);
```
After that `log.txt` will contain debug info, e.g
```text
Array
(
    [user] => username
    [password] => password
    [_path] => credits
    [_time] => 01-10-2017 14:51:29
)
Array
(
    [recipient] => +375296666666
    [message] => test message
    [sender] => sender_name
    [user] => username
    [password] => password
    [_path] => send_sms
    [_time] => 01-10-2017 14:51:29
)
```