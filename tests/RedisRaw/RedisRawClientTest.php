<?php

namespace Ehann\Tests\RedisRaw;

use Ehann\Tests\AbstractTestCase;

class RedisRawClientTest extends AbstractTestCase
{
    public function testThatAdapterCanConnectWithoutPassword(): void
    {
        $info = $this->redisClient->rawCommand('info', []);

        $this->assertStringContainsString('redis_version', $info);
    }
}
