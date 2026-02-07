<?php

namespace App;

use Google\Service\Drive as GoogleDrive;
use Google\Client as GoogleClient;
use Google\Service\Drive\Permission;

class GoogleDriveService
{
    protected $client;
    protected $service;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('filesystems.disks.google.clientId'));
        $this->client->setClientSecret(config('filesystems.disks.google.clientSecret'));
        $this->client->refreshToken(config('filesystems.disks.google.refreshToken'));

        $this->service = new GoogleDrive($this->client);
    }

    public function getAccessToken()
    {
        $token = $this->client->getAccessToken();

        if ($this->client->isAccessTokenExpired()) {
            $this->client->fetchAccessTokenWithRefreshToken(config('filesystems.disks.google.refreshToken'));
            $token = $this->client->getAccessToken();
        }

        return $token['access_token'];
    }

    public function visibilityPublic($googleDriveId)
    {
        $permission = new Permission([
            'role' => 'reader',
            'type' => 'anyone',
        ]);
        $this->service->permissions->create($googleDriveId, $permission);
    }
}
