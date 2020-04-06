<?php

namespace Kregel\Basement\DigitalOcean\Tests\Feature;

use Kregel\Basement\DigitalOcean\Server;
use Kregel\Basement\DigitalOcean\ServerService;
use Kregel\Basement\DigitalOcean\Tests\AbstractTestCase;
use Kregel\Basement\Credential;

class ServerServiceTest extends AbstractTestCase
{
    public function testCreateServerSuccess()
    {
        $credential = new Credential;
        $credential->access_token = getenv('TEST_DIGITAL_OCEAN_TOKEN');

        $service = new ServerService($credential);

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
        $credential = new Credential;
        $credential->access_token = getenv('TEST_DIGITAL_OCEAN_TOKEN');

        $service = new ServerService($credential);

        $regions = $service->findAllRegions();
        $this->assertCount(9, $regions);
    }

    public function testFindAllSizes()
    {
        $credential = new Credential;
        $credential->access_token = getenv('TEST_DIGITAL_OCEAN_TOKEN');

        $service = new ServerService($credential);

        $sizes = $service->findAllSizes();

        $this->assertCount(64, $sizes);
    }

    public function testFindAllServers()
    {
        $credential = new Credential;
        $credential->access_token = getenv('TEST_DIGITAL_OCEAN_TOKEN');

        $service = new ServerService($credential);

        $servers = $service->findAllServers();

        $this->assertGreaterThan(0, $servers);
    }

    public function testPowerOffTestServer()
    {
        // We have to sleep a few seconds to let DO's servers to catch up. Their UI glitches if we create then delete a server too quickly...
        // This timer _may_ need to be increased if they ever rate limit their API for this task/route...
        sleep(10);

        $credential = new Credential;
        $credential->access_token = getenv('TEST_DIGITAL_OCEAN_TOKEN');

        $service = new ServerService($credential);

        $servers = $service->findAllServers();

        $this->assertNotEmpty($servers);

        $server = array_values(array_filter($servers, function (Server $server) {
            return $server->name === 'test-create-success';
        }))[0];

        $this->assertNotNull($server);

        $service->powerOffServer($server->id);
    }

    public function testPowerOnTestServer()
    {
        // We have to sleep a few seconds to let DO's servers to catch up. Their UI glitches if we create then delete a server too quickly...
        // This timer _may_ need to be increased if they ever rate limit their API for this task/route...
        sleep(10);

        $credential = new Credential;
        $credential->access_token = getenv('TEST_DIGITAL_OCEAN_TOKEN');

        $service = new ServerService($credential);

        $servers = $service->findAllServers();

        $this->assertNotEmpty($servers);

        $server = array_values(array_filter($servers, function (Server $server) {
            return $server->name === 'test-create-success';
        }))[0];

        $this->assertNotNull($server);

        $service->powerOnServer($server->id);
    }

    public function testShutdownTestServer()
    {
        // We have to sleep a few seconds to let DO's servers to catch up. Their UI glitches if we create then delete a server too quickly...
        // This timer _may_ need to be increased if they ever rate limit their API for this task/route...
        sleep(10);

        $credential = new Credential;
        $credential->access_token = getenv('TEST_DIGITAL_OCEAN_TOKEN');

        $service = new ServerService($credential);

        $servers = $service->findAllServers();

        $this->assertNotEmpty($servers);

        $server = array_values(array_filter($servers, function (Server $server) {
            return $server->name === 'test-create-success';
        }))[0];
        $this->assertNotNull($server);

        $service->shutdownServer($server->id);
    }

    public function testDeleteTestServer()
    {
        // We have to sleep a few seconds to let DO's servers to catch up. Their UI glitches if we create then delete a server too quickly...
        // This timer _may_ need to be increased if they ever rate limit their API for this task/route...
        sleep(10);

        $credential = new Credential;
        $credential->access_token = getenv('TEST_DIGITAL_OCEAN_TOKEN');

        $service = new ServerService($credential);

        $servers = $service->findAllServers();

        $this->assertNotEmpty($servers);

        $server = array_values(array_filter($servers, function (Server $server) {
            return $server->name === 'test-create-success';
        }))[0];

        $this->assertNotEmpty($server);

        $service->deleteServer($server->id);

        $servers = $service->findAllServers();

        $this->assertNotEmpty($servers);

        $server = array_values(array_filter($servers, function (Server $server) {
            return $server->name === 'test-create-success';
        }))[0];

        $this->assertEmpty($server);
    }
}
