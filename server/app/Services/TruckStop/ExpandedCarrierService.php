<?php

namespace App\Services\Truckstop;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExpandedCarrierService
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $password;

    public function __construct()
    {
        $this->baseUrl  = config('truckstop.base_url');
        $this->clientId = config('truckstop.client_id');
        $this->password = config('truckstop.client_password');
    }

    /**
     * Fetch carrier by RMIS ID (used in Delta flow)
     */
    public function getByRmisId(string $rmisId): ?array
    {
        return $this->fetchCarrier('INSDID', $rmisId);
    }

    /**
     * Fetch carrier by MC Number (used for testing)
     */
    public function getByMcNumber(string $mcNumber): ?array
    {
        // Strip "MC" prefix if present
        $mcNumber = ltrim(strtoupper($mcNumber), 'MC');
        return $this->fetchCarrier('MC', $mcNumber);
    }

    private function fetchCarrier(string $queryType, string $queryId): ?array
    {
        try {
            $response = Http::get("{$this->baseUrl}/_c/std/api/ExpandedCarrierAPI.aspx", [
                'clientID'  => $this->clientId,
                'pwd'       => $this->password,
                'querytype' => $queryType,
                'queryid'   => $queryId,
                'version'   => 13,
            ]);

            if ($response->failed()) {
                Log::error("ExpandedCarrierAPI HTTP error", [
                    'status'  => $response->status(),
                    'queryId' => $queryId,
                ]);
                return null;
            }

            $xml = simplexml_load_string($response->body());

            if (!$xml) {
                Log::error("Failed to parse XML", ['queryId' => $queryId]);
                return null;
            }

            // Check for detached carrier error
            $result = (string) $xml->Header->Result;
            if (str_contains(strtolower($result), 'does not belong')) {
                return ['error' => 'detached', 'rmis_id' => $queryId];
            }

            return $this->parseCarrierXml($xml);

        } catch (\Exception $e) {
            Log::error("ExpandedCarrierAPI exception: " . $e->getMessage());
            return null;
        }
    }

    private function parseCarrierXml(\SimpleXMLElement $xml): array
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
            'timestamp'    => (string) $xml->Header->TimeStamp, // needed for CLEAR
        ];
    }
}
