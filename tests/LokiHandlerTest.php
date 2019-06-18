<?php

namespace Vyuldashev\Monolog\Loki\Tests;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Vyuldashev\Monolog\Loki\LokiFormatter;
use Vyuldashev\Monolog\Loki\LokiHandler;

class LokiHandlerTest extends TestCase
{
    public function test()
    {
        $url = 'http://localhost:3100/api/prom/push';
        $handler = new LokiHandler($url);
        $handler->setFormatter(new LokiFormatter());

        $logger = new Logger('name');
        $logger->pushHandler($handler);

        $logger->debug('Message', [
            'foo' => 'bar',
        ]);
    }
}
