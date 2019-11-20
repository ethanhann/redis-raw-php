<?php

namespace Ehann\Tests\RedisRaw;

use Ehann\Tests\AbstractTestCase;

class RedisClientAdapterTest extends AbstractTestCase
{
    public function testShouldRunInfoCommand()
    {
        $client = $this->makeRedisClientAdapter();
        $expected = 'redis_version';

        $result = $client->rawCommand('INFO');

        $this->assertContains($expected, $result);
    }
}
