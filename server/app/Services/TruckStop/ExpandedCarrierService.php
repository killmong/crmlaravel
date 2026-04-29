<?php

namespace App\Services\Truckstop;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExpandedCarrierService
{
    protected string $expandedUrl = 'https://api.rmissecure.com/_c/std/api/ExpandedCarrierAPI.aspx';
    protected string $clientId;
    protected string $password;

    public function __construct()
    {
        $this->clientId = config('truckstop.client_id');  // ✅ 7327
        $this->password = config('truckstop.password');   // ✅ correct key
    }

    protected function http()
    {
        $client = Http::withHeaders(['accept' => 'application/json']);

        if (app()->environment('local')) {
            $client = $client->withoutVerifying(); // SSL fix for local dev
        }

        return $client;
    }

// Get carrier info using RMIS internal ID (INSDID)
// Used when you already have the RMIS system identifier
public function getByRmisId(string $rmisId): ?array
{
    return $this->fetchCarrier('INSDID', $rmisId);
}

// Get carrier info using MC Number (Motor Carrier number)
// Cleans input like "MC12345" → "12345" before querying API
public function getByMcNumber(string $mcNumber): ?array
{
    // Normalize MC number (remove "MC" prefix and force uppercase)
    $mcNumber = ltrim(strtoupper($mcNumber), 'MC');

    return $this->fetchCarrier('MC', $mcNumber);
}

// Core method that actually calls the RMIS Expanded Carrier API
// $queryType = 'MC' or 'INSDID'
// $queryId = the value being searched
private function fetchCarrier(string $queryType, string $queryId): ?array
{
    try {
        // Send GET request to RMIS API with authentication + query params
        $response = $this->http()->get($this->expandedUrl, [
            'clientID'  => $this->clientId,   // API client ID from config
            'pwd'       => $this->password,   // API password from config
            'querytype' => $queryType,        // Type of lookup (MC or INSDID)
            'queryid'   => $queryId,          // Actual identifier value
            'version'   => 13,                // API version (required by RMIS)
        ]);

        // If API request fails (4xx/5xx), log error and stop
        if ($response->failed()) {
            Log::error('ExpandedCarrierAPI HTTP error', [
                'status'  => $response->status(),
                'queryId' => $queryId,
            ]);
            return null;
        }

        // Convert XML response into PHP object
        $xml = simplexml_load_string($response->body());

        // If XML parsing fails, log and stop
        if (!$xml) {
            Log::error('Failed to parse XML', ['queryId' => $queryId]);
            return null;
        }

        // Read API result message from response header
        $result = (string) $xml->Header->Result;

        // Handle special case:
        // If carrier is "detached" (no longer belongs to your RMIS account)
        if (str_contains(strtolower($result), 'does not belong')) {
            return [
                'error'   => 'detached',
                'rmis_id' => $queryId
            ];
        }

        // Capture timestamp from API response headers
        // Used for tracking freshness or delta updates
        $timestamp = $response->header('TimeStamp')
                  ?? $response->header('Last-Modified')
                  ?? now()->toIso8601String();

        // Parse XML into structured array (custom method)
        return $this->parseCarrierXml($xml, $timestamp);

    } catch (\Exception $e) {
        // Catch unexpected errors (network issues, parsing errors, etc.)
        Log::error('ExpandedCarrierAPI exception: ' . $e->getMessage());
        return null;
    }
}

    private function parseCarrierXml(\SimpleXMLElement $xml, string $timestamp): array
    {
        $carrier = $xml->Carrier;

        return [
            'rmis_id'      => (string) $carrier->RMISCarrierID,
            'company_name' => (string) $carrier->CompanyName,
            'mc_number'    => (string) $carrier->MCNumber,
            'dot_number'   => (string) $carrier->DOTNumber,
            'city'         => (string) $carrier->City,
            'state'        => (string) $carrier->St,
            'phone'        => (string) $carrier->Phone,
            'email'        => (string) $carrier->Email,
            'is_certified' => strtolower((string) $carrier->IsCertified) === 'true',
            'timestamp'    => $timestamp, // ✅ from header, not XML body
        ];
    }
}