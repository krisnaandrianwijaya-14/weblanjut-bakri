<?php

namespace App\Support;

use App\Models\Client;
use RuntimeException;

final class ClientContext
{
    private ?Client $client = null;

    public function set(Client $client): void
    {
        $this->client = $client;
    }

    public function clear(): void
    {
        $this->client = null;
    }

    public function has(): bool
    {
        return $this->client !== null;
    }

    public function client(): Client
    {
        return $this->client
            ?? throw new RuntimeException('ClientContext belum diisi.');
    }

    public function id(): int
    {
        return $this->client()->id;
    }

    public function idOrNull(): ?int
    {
        return $this->client?->id;
    }
    
}
