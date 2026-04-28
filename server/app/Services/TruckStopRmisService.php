<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TruckstopRmisService
{
    protected string $clientId;
    protected string $password;
    protected string $deltaUrl    = 'https://api.rmissecure.com/_c/std/api/DeltaAPI.aspx';
    protected string $expandedUrl = 'https://api.rmissecure.com/_c/std/api/ExpandedCarrierAPI.aspx';
    protected string $documentUrl = 'https://api.rmissecure.com/_c/std/api/DocumentAPI.aspx';

    public function __construct()
    {
        $this->clientId = config('truckstop.client_id'); // 7327
        $this->password = config('truckstop.password');
    }

    // ─────────────────────────────────────────────
    // DELTA API  →  POST + JSON body
    // ─────────────────────────────────────────────

    /**
     * SUMMARY: How many carriers are waiting in the queue
     */
    public function getDeltaSummary(): int
    {
        $response = Http::withHeaders([
            'accept'       => 'application/json',
            'content-type' => 'application/json',
        ])->post($this->deltaUrl, [
            'ClientID'       => $this->clientId,
            'ClientPassword' => $this->password,
            'APIMode'        => 'Summary',
        ]);

        if (!$response->successful()) {
            Log::error('Delta SUMMARY failed', ['body' => $response->body()]);
            return 0;
        }

        $data = $response->json();
        return (int) ($data['RMISDeltaAPI']['SUMMARY']['TotalInsdIDs'] ?? 0);
    }

    /**
     * FETCH: Pull up to $maxRecs carrier RMIS IDs from the queue
     */
    public function fetchDeltaCarriers(int $maxRecs = 100): array
    {
        $response = Http::withHeaders([
            'accept'       => 'application/json',
            'content-type' => 'application/json',
        ])->post($this->deltaUrl, [
            'ClientID'       => $this->clientId,
            'ClientPassword' => $this->password,
            'APIMode'        => 'Fetch',
            'MaxRecs'        => $maxRecs,
        ]);

        if (!$response->successful()) {
            Log::error('Delta FETCH failed', ['body' => $response->body()]);
            return [];
        }

        $data     = $response->json();
        $carriers = $data['RMISDeltaAPI']['FETCH']['InsdIDs'] ?? [];

        // Normalize single result to array
        if (isset($carriers['InsdID'])) {
            $carriers = [$carriers];
        }

        Log::info('Delta FETCH: ' . count($carriers) . ' carriers in queue');
        return $carriers;
    }

    /**
     * CLEAR: Remove processed carriers from the queue
     * Requires ID + timestamp (from Expanded Carrier response header)
     */
    public function clearDeltaCarriers(array $processedCarriers): bool
    {
        // Build the InsdIDs array: [['InsdID' => '...', 'TimeStamp' => '...'], ...]
        $insdIds = array_map(fn($c) => [
            'InsdID'    => $c['id'],
            'TimeStamp' => $c['timestamp'],
        ], $processedCarriers);

        $response = Http::withHeaders([
            'accept'       => 'application/json',
            'content-type' => 'application/json',
        ])->post($this->deltaUrl, [
            'ClientID'       => $this->clientId,
            'ClientPassword' => $this->password,
            'APIMode'        => 'Clear',
            'InsdIDs'        => $insdIds,
        ]);

        if (!$response->successful()) {
            Log::error('Delta CLEAR failed', ['body' => $response->body()]);
            return false;
        }

        Log::info('Delta CLEAR: cleared ' . count($processedCarriers) . ' carriers');
        return true;
    }

    // ─────────────────────────────────────────────
    // EXPANDED CARRIER API  →  GET + query params
    // ─────────────────────────────────────────────

    /**
     * Fetch full carrier XML by RMIS Insured ID (from Delta FETCH)
     * Returns ['xml' => SimpleXMLElement, 'timestamp' => string]
     */
    public function fetchExpandedCarrier(string $insdId): ?array
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
        ])->get($this->expandedUrl, [
            'clientID' => $this->clientId,
            'pwd'      => $this->password,
            'insdID'   => $insdId,
            'version'  => '1',
        ]);

        if (!$response->successful()) {
            Log::warning("Expanded Carrier failed for insdID: $insdId", [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return null;
        }

        // ⚠️ Timestamp MUST come from the response header — used in CLEAR step
        $timestamp = $response->header('TimeStamp')
                  ?? $response->header('Last-Modified')
                  ?? now()->toIso8601String();

        $xml    = simplexml_load_string($response->body());
        $result = (string) ($xml->Header->Result ?? '');

        if ($result !== 'SUCCESS') {
            Log::warning("Expanded Carrier API error for insdID $insdId: $result");
            return null;
        }

        return ['xml' => $xml, 'timestamp' => $timestamp];
    }

    /**
     * Fetch carrier by MC Number — useful for testing with MC9999201 / MC9979682
     */
    public function fetchCarrierByMC(string $mcNumber): ?array
    {
        $response = Http::get($this->expandedUrl, [
            'clientID' => $this->clientId,
            'pwd'      => $this->password,
            'MCNumber' => $mcNumber,
            'version'  => '1',
        ]);

        if (!$response->successful()) {
            Log::warning("Expanded Carrier failed for MC: $mcNumber");
            return null;
        }

        $timestamp = $response->header('TimeStamp') ?? now()->toIso8601String();
        $xml       = simplexml_load_string($response->body());

        return ['xml' => $xml, 'timestamp' => $timestamp];
    }

    // ─────────────────────────────────────────────
    // DOCUMENT API  →  GET + query params
    // ─────────────────────────────────────────────

    public function fetchDocument(string $insdId, string $documentId, string $documentType): ?string
    {
        $response = Http::get($this->documentUrl, [
            'clientID'     => $this->clientId,
            'pwd'          => $this->password,
            'insdID'       => $insdId,
            'documentID'   => $documentId,
            'documentType' => $documentType,
            'version'      => '1',
        ]);

        if (!$response->successful()) {
            return null;
        }

        $xml = simplexml_load_string($response->body());
        // Returns base64-encoded binary — decode before saving
        return base64_decode((string) ($xml->DocumentData ?? ''));
    }
}