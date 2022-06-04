<?php

namespace Ehann\Tests;

use Ehann\RedisRaw\AbstractRedisRawClient;
use Ehann\RedisRaw\PhpRedisAdapter;
use Ehann\RedisRaw\PredisAdapter;
use Ehann\RedisRaw\RedisClientAdapter;
use Ehann\RedisRaw\RedisRawClientInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected RedisRawClientInterface $redisClient;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $host = getenv('REDIS_HOST') ?? '127.0.0.1';
        $port = getenv('REDIS_PORT') ?? 6379;
        $db = getenv('REDIS_DB') ?? 0;
        $password = getenv('REDIS_PASSWORD');
        if (!$password) {
            $password = null;
        }

        $factoryMethod = 'make' . getenv('REDIS_LIBRARY') . 'Adapter';
        $this->redisClient = $this->$factoryMethod()->connect($host, $port, $db, $password);

        if (getenv('IS_LOGGING_ENABLED')) {
            $logger = new Logger('Ehann\RedisRaw');
            $logger->pushHandler(new StreamHandler(getenv('LOG_FILE'), Logger::DEBUG));
            $this->redisClient->setLogger($logger);
        }
    }

    protected function makePhpRedisAdapter(): RedisRawClientInterface
    {
        return new PhpRedisAdapter();
    }

    protected function makePredisAdapter(): RedisRawClientInterface
    {
        return new PredisAdapter();
    }

    protected function makeRedisClientAdapter(): RedisRawClientInterface
    {
        return new RedisClientAdapter();
    }

    protected function isUsingPredis(): bool
    {
        return getenv('REDIS_LIBRARY') === AbstractRedisRawClient::PREDIS_LIBRARY;
    }

    protected function isUsingPhpRedis(): bool
    {
        return getenv('REDIS_LIBRARY') === AbstractRedisRawClient::PHP_REDIS_LIBRARY;
    }

    protected function isUsingRedisClient(): bool
    {
        return getenv('REDIS_LIBRARY') === AbstractRedisRawClient::REDIS_CLIENT_LIBRARY;
    }
}
