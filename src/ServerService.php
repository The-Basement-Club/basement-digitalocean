<?php

namespace Kregel\Basement\DigitalOcean;

use DigitalOceanV2\DigitalOceanV2;
use DigitalOceanV2\Entity\Region;
use Kregel\Basement\Server as BasementServer;
use Kregel\Basement\SshKey as BasementSshKey;
use Kregel\Basement\ServerServiceContract;
use DigitalOceanV2\Entity\Size as DigitalOceanSize;
use DigitalOceanV2\Entity\Region as DigitalOceanRegion;
use DigitalOceanV2\Entity\Droplet as DigitalOceanServer;

class ServerService implements ServerServiceContract
{
    /**
     * @var DigitalOceanV2
     */
    protected $digitalOcean;

    public function __construct(DigitalOceanV2 $doSdk)
    {
        $this->digitalOcean = $doSdk;
    }

    public function createServer(array $config): BasementServer
    {
        [
            'name' => $name,
            'region' => $region,
            'size' => $size,
            'image' => $image,
            'backups' => $backups,
            'ipv6' => $ipv6,
            'private_networking' => $privateNetworking,
            'ssh_keys' => $sshKeys,
            'user_data' => $userData,
            'monitoring' => $monitoring,
            'volumes' => $volumes,
            'tags' => $tags,
            'wait' => $wait,
            'wait_timeout' => $waitTimeout,
        ] = array_merge([
            'ipv6' => false,
            'private_networking' => true,
            'monitoring' => true,
            'wait' => false,
            'wait_timeout' => 300
        ], $config);

        /** @var DigitalOceanServer $server */
        $server = $this->digitalOcean->droplet()->create(
            $name,
            $region,
            $size,
            $image,
            $backups,
            $ipv6,
            $privateNetworking,
            $sshKeys,
            $userData,
            $monitoring,
            $volumes,
            $tags,
            $wait,
            $waitTimeout
        );

        return new Server($server->toArray());
    }

    public function createServerKey(array $config): BasementSshKey
    {
        $key = $this->digitalOcean->key()->create($config['name'], $config['public_key']);

        return new SshKey($key->toArray());
    }

    public function findAllRegions(): array
    {
        $regions = $this->digitalOcean->region()->getAll();

        return array_map(function (DigitalOceanRegion $region) {
            return new Region($region->toArray());
        }, $regions);
    }

    public function findAllSizes(): array
    {
        $sizes = $this->digitalOcean->size()->getAll();

        return array_map(function (DigitalOceanSize $size) {
            return new Size($size->toArray());
        }, $sizes);
    }

    public function findAllServers(): array
    {
        $servers = $this->digitalOcean->droplet()->getAll();

        return array_map(function (DigitalOceanServer $size) {
            return new Server($size->toArray());
        }, $servers);
    }

    public function removeServerKey($identifier): void
    {
        $this->digitalOcean->key()->delete($identifier);
    }

    public function deleteServer(int $identifier): void
    {
        $this->digitalOcean->droplet()->delete($identifier);
    }

    public function powerOnServer(int $identifier): void
    {
        $this->digitalOcean->droplet()->powerOn($identifier);
    }

    public function powerOffServer(int $identifier): void
    {
        $this->digitalOcean->droplet()->powerOff($identifier);
    }

    public function shutdownServer(int $identifier): void
    {
        $this->digitalOcean->droplet()->shutdown($identifier);
    }

    public function rebootServer(int $identifier): void
    {
        $this->digitalOcean->droplet()->reboot($identifier);
    }
}
