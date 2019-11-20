<?php

namespace Ehann\Tests\RedisRaw;

use Ehann\Tests\AbstractTestCase;

class PredisAdapterTest extends AbstractTestCase
{
    public function testShouldRunInfoCommand()
    {
        $client = $this->makePredisAdapter();
        $expected = 'redis_version';

        $result = $client->rawCommand('INFO');

        $this->assertContains($expected, $result);
    }
}
