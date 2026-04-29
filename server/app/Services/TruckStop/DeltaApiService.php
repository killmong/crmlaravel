<?php

namespace App\Services;          // Service layer (handles external API logic)

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeltaApiService
{
    private string $baseUrl;   // Delta API endpoint URL
    private string $clientId;  // API client ID (from config)
    private string $password;  // API password (from config)
    private bool $testMode;    // Toggle for fake/test responses

    public function __construct()
    {
        // Load API credentials + settings from config
        $this->baseUrl  = config('truckstop.delta_url');
        $this->clientId = config('truckstop.client_id');
        $this->password = config('truckstop.password');   // Auth password
        $this->testMode = config('truckstop.test_mode', false); // Enable mock mode
    }

    // Create reusable HTTP client with JSON headers
    protected function http()
    {
        $client = Http::withHeaders([
            'accept'       => 'application/json',
            'content-type' => 'application/json',
        ]);

        // Disable SSL verification in local dev (avoids cert issues)
        if (app()->environment('local')) {
            $client = $client->withoutVerifying();
        }

        return $client;
    }

    // STEP 1: Get total number of updated carriers waiting in Delta queue
    public function getSummary(): int
    {
        // In test mode, return fake count instead of calling API
        if ($this->testMode) {
            return 2; // pretend 2 carriers need processing
        }

        // Call Delta API in "Summary" mode
        $response = $this->http()->post($this->baseUrl, [
            'ClientID'       => $this->clientId,
            'ClientPassword' => $this->password,
            'APIMode'        => 'Summary', // tells API to return count only
        ]);

        // Handle API failure
        if (!$response->successful()) {
            Log::error('Delta SUMMARY failed', ['body' => $response->body()]);
            return 0;
        }

        // Extract total number of updated carriers (InsdIDs)
        return (int) ($response->json('RMISDeltaAPI.SUMMARY.TotalInsdIDs') ?? 0);
    }

    // STEP 2: Fetch list of carriers that have updates (delta changes)
    public function fetch(int $maxRecs = 100): array
    {
        // Return fake IDs in test mode
        if ($this->testMode) {
            return ['340083', '340084']; // mock RMIS IDs
        }

        // Call Delta API in "Fetch" mode to retrieve updated carriers
        $response = $this->http()->post($this->baseUrl, [
            'ClientID'       => $this->clientId,
            'ClientPassword' => $this->password,
            'APIMode'        => 'Fetch',   // tells API to return changed carriers
            'MaxRecs'        => $maxRecs,  // limit number of records returned
        ]);

        // Handle API failure
        if (!$response->successful()) {
            Log::error('Delta FETCH failed', ['body' => $response->body()]);
            return [];
        }

        // Extract carrier IDs from response
        $carriers = $response->json('RMISDeltaAPI.FETCH.InsdIDs') ?? [];

        // Normalize response if API returns a single object instead of array
        if (isset($carriers['InsdID'])) {
            $carriers = [$carriers];
        }

        return $carriers;
    }

    // STEP 3: Clear processed carriers from Delta queue
    // This tells RMIS "we have processed these updates"
    public function clear(array $carriers): bool
    {
        // Skip real API call in test mode
        if ($this->testMode) {
            Log::info('Test mode: Skipping real Delta CLEAR call');
            return true;
        }

        // Format carriers into required structure for API
        // Each carrier needs ID + timestamp (from Expanded API)
        $insdIds = array_map(fn($c) => [
            'InsdID'    => $c['id'],         // RMIS ID
            'TimeStamp' => $c['timestamp'],  // required for confirmation
        ], $carriers);

        // Call Delta API in "Clear" mode
        $response = $this->http()->post($this->baseUrl, [
            'ClientID'       => $this->clientId,
            'ClientPassword' => $this->password,
            'APIMode'        => 'Clear',  // tells API to remove processed records
            'InsdIDs'        => $insdIds,
        ]);

        // Handle API failure
        if (!$response->successful()) {
            Log::error('Delta CLEAR failed', ['body' => $response->body()]);
            return false;
        }

        // Log success for tracking/debugging
        Log::info('Delta CLEAR: cleared ' . count($carriers) . ' carriers');

        return true;
    }
}