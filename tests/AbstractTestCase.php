<?php

namespace Ehann\Tests;

use Ehann\RedisRaw\PhpRedisRawAdapter;
use Ehann\RedisRaw\PredisAdapter;
use Ehann\RedisRaw\RedisClientAdapter;
use Ehann\RedisRaw\RedisClientInterface;
use Ehann\RedisRaw\RedisRawClient;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /** @var string */
    protected $indexName;
    /** @var RedisClientInterface */
    protected $redisClient;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $factoryMethod = 'make' . getenv('REDIS_LIBRARY') . 'Adapter';
        $this->redisClient = $this->$factoryMethod();

        if (getenv('IS_LOGGING_ENABLED')) {
            $logger = new Logger('Ehann\RedisRaw');
            $logger->pushHandler(new StreamHandler(getenv('LOG_FILE'), Logger::DEBUG));
            $this->redisClient->setLogger($logger);
        }
    }

    protected function makePhpRedisAdapter(): RedisClientInterface
    {
        return (new PhpRedisRawAdapter())->connect(
            getenv('REDIS_HOST') ?? '127.0.0.1',
            getenv('REDIS_PORT') ?? 6379,
            getenv('REDIS_DB') ?? 0
        );
    }

    protected function makePredisAdapter(): RedisClientInterface
    {
        return (new PredisAdapter())->connect(
            getenv('REDIS_HOST') ?? '127.0.0.1',
            getenv('REDIS_PORT') ?? 6379,
            getenv('REDIS_DB') ?? 0
        );
    }

    protected function makeRedisClientAdapter(): RedisClientInterface
    {
        return (new RedisClientAdapter())->connect(
            getenv('REDIS_HOST') ?? '127.0.0.1',
            getenv('REDIS_PORT') ?? 6379,
            getenv('REDIS_DB') ?? 0
        );
    }

    protected function isUsingPredis()
    {
        return getenv('REDIS_LIBRARY') === RedisRawClient::PREDIS_LIBRARY;
    }

    protected function isUsingPhpRedis()
    {
        return getenv('REDIS_LIBRARY') === RedisRawClient::PHP_REDIS_LIBRARY;
    }

    protected function isUsingRedisClient()
    {
        return getenv('REDIS_LIBRARY') === RedisRawClient::REDIS_CLIENT_LIBRARY;
    }
}
