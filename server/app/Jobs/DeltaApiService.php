<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class DeltaApiService implements ShouldQueue
{
    use Queueable;

    private string $baseUrl;
    private string $clientId;
    private string $password;
    private bool $testMode;

public function __construct()
{
    $this->baseUrl  = config('truckstop.base_url');
    $this->clientId = config('truckstop.client_id');
    $this->password = config('truckstop.client_password');
    $this->testMode = config('truckstop.test_mode', false);
}

public function getSummary(): int
{
    // In test mode pretend there are 2 carriers waiting
    if ($this->testMode) {
        return 2;
    }

    // ... real API call
    return 0;
}

public function fetch(): array
{
    // Return the two test RMIS IDs
    if ($this->testMode) {
        return ['340083', '340084'];
    }

    // ... real API call
    return [];
}

public function clear(array $carriers): bool
{
    // Pretend clear always succeeds in test mode
    if ($this->testMode) {
        Log::info('Test mode: Skipping real Delta CLEAR call');
        return true;
    }

    // ... real API call
    return false;
}
}
