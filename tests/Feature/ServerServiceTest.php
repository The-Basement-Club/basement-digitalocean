<?php

namespace Kregel\Basement\DigitalOcean\Tests\Feature;

use DigitalOceanV2\Adapter\GuzzleHttpAdapter;
use DigitalOceanV2\DigitalOceanV2;
use Kregel\Basement\DigitalOcean\ServerService;
use Kregel\Basement\DigitalOcean\Tests\AbstractTestCase;

class ServerServiceTest extends AbstractTestCase
{
    public function testCreateServerSuccess()
    {
        $this->assertTrue(true);
        // Since this actually creates a DO Droplet, I'm disabling the test since automatically removing the droplets also breaks DO...
        return;
        $service = new ServerService(
            new DigitalOceanV2(
                new GuzzleHttpAdapter(getenv('TEST_DIGITAL_OCEAN_TOKEN'))
            )
        );

        $server = $service->createServer([
            'name' => 'test-create-success',
            'region' => 'nyc3',
            'size' => 's-1vcpu-1gb',
            'image' => 'ubuntu-16-04-x64',
            'backups' => false,
            'ipv6' => false,
            'private_networking' => false,
            'ssh_keys' => [],
            'user_data' => null,
            'monitoring' => null,
            'volumes' => [],
            'tags' => ['test'],
        ]);

        $this->assertSame('test-create-success', $server->name);
    }

    public function testFindAllRegions()
    {
        $service = new ServerService(
            new DigitalOceanV2(
                new GuzzleHttpAdapter(getenv('TEST_DIGITAL_OCEAN_TOKEN'))
            )
        );

        $regions = $service->findAllRegions();
        $this->assertCount(9, $regions);
    }

    public function testFindAllSizes()
    {
        $service = new ServerService(
            new DigitalOceanV2(
                new GuzzleHttpAdapter(getenv('TEST_DIGITAL_OCEAN_TOKEN'))
            )
        );

        $sizes = $service->findAllSizes();

        $this->assertCount(64, $sizes);
    }

    public function testFindAllServers()
    {
        $service = new ServerService(
            new DigitalOceanV2(
                new GuzzleHttpAdapter(getenv('TEST_DIGITAL_OCEAN_TOKEN'))
            )
        );

        $servers = $service->findAllServers();

        $this->assertGreaterThan(0, $servers);
    }
}
