<?php

namespace Ehann\RedisRaw;

use Ehann\RedisRaw\Exceptions\RawCommandErrorException;
use Ehann\RedisRaw\Exceptions\UnsupportedRedisDatabaseException;
use Exception;
use Psr\Log\LoggerInterface;

abstract class AbstractRedisRawClient implements RedisRawClientInterface
{
    public const PREDIS_LIBRARY = 'Predis';
    public const PHP_REDIS_LIBRARY = 'PhpRedis';
    public const REDIS_CLIENT_LIBRARY = 'RedisClient';

    public $redis;
    /** @var  LoggerInterface */
    protected $logger;

    public function connect($hostname = '127.0.0.1', $port = 6379, $db = 0, $password = null): RedisRawClientInterface
    {
        return $this;
    }

    public function flushAll()
    {
        $this->redis->flushAll();
    }

    public function multi(bool $usePipeline = false)
    {
    }

    public function rawCommand(string $command, array $arguments)
    {
    }

    public function prepareRawCommandArguments(string $command, array $arguments): array
    {
        array_unshift($arguments, $command);
        if ($this->logger) {
            $this->logger->debug(implode(' ', $arguments));
        }
        return $arguments;
    }

    /**
     * @param $rawResult
     * @throws RawCommandErrorException
     * @throws UnsupportedRedisDatabaseException
     */
    public function validateRawCommandResults($rawResult)
    {
        $isRawResultException = $rawResult instanceof Exception;
        $message = $isRawResultException ? $rawResult->getMessage() : $rawResult;

        if (!is_string($message)) {
            return;
        }
        $message = strtolower($message);
        if ($message === 'cannot create index on db != 0') {
            throw new UnsupportedRedisDatabaseException();
        }
        if ($isRawResultException) {
            throw new RawCommandErrorException('', 0, $rawResult);
        }
    }

    public function normalizeRawCommandResult($rawResult)
    {
        return $rawResult === 'OK' ? true : $rawResult;
    }

    public function setLogger(LoggerInterface $logger): RedisRawClientInterface
    {
        $this->logger = $logger;
        return $this;
    }
}
