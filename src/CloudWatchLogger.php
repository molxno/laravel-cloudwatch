<?php

namespace Molxno\LaravelCloudwatch;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Aws\Exception\AwsException;

class CloudWatchLogger extends AbstractProcessingHandler
{
    protected CloudWatchLogsClient $client;
    protected string $logGroup;
    protected string $logStream;
    protected ?string $sequenceToken = null;

    public function __construct(array $config, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        try {
            $this->client = new CloudWatchLogsClient([
                'version'     => 'latest',
                'region'      => $config['region'],
                'credentials' => [
                    'key'    => $config['key'],
                    'secret' => $config['secret'],
                ],
            ]);

            $this->logGroup  = $config['log_group'];
            $this->logStream = $config['log_stream'];
        } catch (AwsException $e) {
            throw new \RuntimeException("Error al conectar con CloudWatch: " . $e->getMessage());
        }
    }

    protected function write(\Monolog\LogRecord $record): void
    {
        $params = [
            'logGroupName'  => $this->logGroup,
            'logStreamName' => $this->logStream,
            'logEvents'     => [
                [
                    'timestamp' => round(microtime(true) * 1000),
                    'message'   => $record->formatted,
                ],
            ],
        ];

        try {
            if ($this->sequenceToken) {
                $params['sequenceToken'] = $this->sequenceToken;
            }

            $result = $this->client->putLogEvents($params);

            if (isset($result['nextSequenceToken'])) {
                $this->sequenceToken = $result['nextSequenceToken'];
            }
        } catch (AwsException $e) {
            throw new \RuntimeException("Error al enviar log a CloudWatch: " . $e->getMessage());
        }
    }
}
