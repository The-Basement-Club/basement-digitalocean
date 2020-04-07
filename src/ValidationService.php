<?php

namespace Kregel\Basement\DigitalOcean;

use Kregel\Basement\NotImplementedException;
use Kregel\Basement\ValidationServiceContract;

class ValidationService implements ValidationServiceContract
{
    public function serverRules(): array
    {
        return [
            'name' => 'required|string',
            'region' => 'required|string',
            'size' => 'required|string',
            'image' => 'required|string',
            'ssh_keys' => 'required|array',
            'backups' => 'boolean',
            'private_networking' => 'boolean',
            'user_data' => 'string',
            'monitoring' => 'boolean',
            'volumes' => 'array',
            'tags' => 'array',
        ];
    }

    public function serverKeyRules(): array
    {
        return [
            'name' => 'required|string',
            'public_key' => 'required|string'
        ];
    }

    public function domainRules(): array
    {
        throw new NotImplementedException("Domain validation is not a feature.");
    }
}
