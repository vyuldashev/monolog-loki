<?php

namespace Vyuldashev\Monolog\Loki;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class LokiHandler extends AbstractProcessingHandler
{
    protected $url;

    public function __construct($url, $level = Logger::DEBUG, $bubble = true)
    {
        $this->url = $url;

        parent::__construct($level, $bubble);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param array $record
     * @return void
     */
    protected function write(array $record)
    {
        $formatted = $record['formatted'];

        echo $formatted . PHP_EOL;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /** @noinspection CurlSslServerSpoofingInspection */
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formatted);
        curl_exec($ch);
        curl_close($ch);
    }
}
