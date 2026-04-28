<?php

namespace App\Jobs;

use App\Models\Carrier;
use App\Services\TruckstopRmisService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SyncCarriersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle(TruckstopRmisService $rmis): void
    {
        // STEP 1: FETCH carrier IDs from Delta API
        $deltaCarriers = $rmis->fetchDeltaCarriers();

        if (empty($deltaCarriers)) {
            Log::info('No carriers to sync.');
            return;
        }

        $processedCarriers = [];

        // STEP 2: For each ID, call the Expanded Carrier API
        foreach ($deltaCarriers as $delta) {
            $result = $rmis->fetchExpandedCarrier($delta['id']);

            if (!$result) {
                continue; // skip failed fetches, don't add to CLEAR list
            }

            $xml       = $result['xml'];
            $timestamp = $result['timestamp']; // from response header

            // Map XML fields to your DB columns
            Carrier::updateOrCreate(
                ['carrier_id' => $delta['id']],
                [
                    'name'        => (string) ($xml->name ?? ''),
                    'dot_number'  => (string) ($xml->dotNumber ?? ''),
                    'mc_number'   => (string) ($xml->mcNumber ?? ''),
                    'status'      => (string) ($xml->status ?? ''),
                    'raw_xml'     => $xml->asXML(),
                    'synced_at'   => now(),
                ]
            );

            // Track with the header timestamp for safe CLEAR
            $processedCarriers[] = [
                'id'        => $delta['id'],
                'timestamp' => $timestamp,
            ];
        }

        // STEP 3: CLEAR — Only clear successfully processed carriers
        if (!empty($processedCarriers)) {
            $rmis->clearDeltaCarriers($processedCarriers);
        }

        Log::info('Carrier sync complete. Processed: ' . count($processedCarriers));
    }
}