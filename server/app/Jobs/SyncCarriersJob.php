<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Models\Carrier;
use App\Models\CarrierSyncLog;
use App\Services\Truckstop\DeltaApiService;
use App\Services\Truckstop\ExpandedCarrierService;

class SyncCarriersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;       // retry up to 3 times if it fails
    public int $timeout = 120;     // max 120 seconds (requirement: clear within 2 min)

    public function handle(
        DeltaApiService        $deltaApi,
        ExpandedCarrierService $expandedApi
    ): void {
        Log::info('🚛 SyncCarriersJob started');

        // ─────────────────────────────────────────────
        // STEP 1: Check if there's anything in the queue
        // ─────────────────────────────────────────────
        $summary = $deltaApi->getSummary();

        if ($summary === 0) {
            Log::info('✅ Delta queue is empty. Nothing to sync.');
            return;
        }

        Log::info("📦 Delta queue has {$summary} carriers to process.");

        // ─────────────────────────────────────────────
        // STEP 2: Loop until queue is completely empty
        // Requirement: keep looping until no updates remain
        // ─────────────────────────────────────────────
        do {
            // STEP 2a: FETCH a batch of carrier IDs from Delta
            $batch = $deltaApi->fetch();

            if (empty($batch)) {
                Log::info('✅ No more carriers in Delta queue.');
                break;
            }

            Log::info('📋 Fetched batch of ' . count($batch) . ' carriers from Delta.');

            // This array collects [rmis_id + timestamp] pairs for the CLEAR call
            $clearList = [];

            // ─────────────────────────────────────────────
            // STEP 2b: For each carrier ID → call Expanded Carrier API
            // ─────────────────────────────────────────────
            foreach ($batch as $rmisId) {

                Log::info("🔍 Fetching expanded data for RMIS ID: {$rmisId}");

                // Log that we fetched this ID from Delta
                $this->log($rmisId, 'fetched', 'Fetched from Delta queue');

                // Call Expanded Carrier API
                $carrierData = $expandedApi->getByRmisId($rmisId);

                // ── Case 1: API call completely failed ──
                if ($carrierData === null) {
                    Log::error("❌ Failed to fetch carrier {$rmisId}");
                    $this->log($rmisId, 'failed', 'Expanded Carrier API returned null');

                    // Still add to clear list so it doesn't block the queue
                    // Use current time as fallback timestamp
                    $clearList[] = [
                        'rmis_id'   => $rmisId,
                        'timestamp' => now()->toIso8601String(),
                    ];
                    continue;
                }

                // ── Case 2: Carrier is detached from your account ──
                // Requirement 4: still clear detached carriers from queue
                if (isset($carrierData['error']) && $carrierData['error'] === 'detached') {
                    Log::warning("⚠️  Carrier {$rmisId} is detached from account.");
                    $this->log($rmisId, 'detached', 'Carrier does not belong to this client');

                    // Mark as detached in DB if it exists
                    Carrier::where('rmis_id', $rmisId)
                           ->update(['status' => 'detached']);

                    // Still must be cleared from queue
                    $clearList[] = [
                        'rmis_id'   => $rmisId,
                        'timestamp' => now()->toIso8601String(),
                    ];
                    continue;
                }

                // ── Case 3: Success — save/update carrier in DB ──
                $this->log($rmisId, 'expanded', 'Expanded Carrier API returned data successfully');

                $this->saveCarrier($carrierData);

                // Collect the timestamp from XML header for CLEAR call
                // Requirement: use the timestamp from the carrier's XML header
                $clearList[] = [
                    'rmis_id'   => $rmisId,
                    'timestamp' => $carrierData['timestamp'],
                ];
            }

            // ─────────────────────────────────────────────
            // STEP 2c: CLEAR the entire batch from Delta queue
            // Requirement: clear within 2 minutes of fetching
            // ─────────────────────────────────────────────
            if (!empty($clearList)) {
                $cleared = $deltaApi->clear($clearList);

                if ($cleared) {
                    foreach ($clearList as $item) {
                        $this->log($item['rmis_id'], 'cleared', 'Cleared from Delta queue');
                    }
                    Log::info('🧹 Cleared ' . count($clearList) . ' carriers from Delta queue.');
                } else {
                    Log::error('❌ Delta CLEAR call failed for this batch.');
                }
            }

            // Check if more remain in the queue
            $remaining = $deltaApi->getSummary();
            Log::info("🔁 Remaining in Delta queue: {$remaining}");

        } while ($remaining > 0);  // keep looping until empty

        Log::info('✅ SyncCarriersJob completed.');
    }

    // ─────────────────────────────────────────────
    // Save or update carrier in the database
    // ─────────────────────────────────────────────
    private function saveCarrier(array $data): void
    {
        Carrier::updateOrCreate(
            // Find by rmis_id (unique identifier)
            ['rmis_id' => $data['rmis_id']],

            // Update or create with these values
            [
                'mc_number'      => $data['mc_number']      ?? null,
                'dot_number'     => $data['dot_number']      ?? null,
                'tax_id'         => $data['tax_id']          ?? null,
                'company_name'   => $data['company_name']    ?? null,
                'contact_name'   => $data['contact_name']    ?? null,
                'title'          => $data['title']           ?? null,
                'address_1'      => $data['address_1']       ?? null,
                'address_2'      => $data['address_2']       ?? null,
                'city'           => $data['city']            ?? null,
                'state'          => $data['state']           ?? null,
                'zip'            => $data['zip']             ?? null,
                'phone'          => $data['phone']           ?? null,
                'fax'            => $data['fax']             ?? null,
                'email'          => $data['email']           ?? null,
                'is_certified'   => $data['is_certified']    ?? false,
                'status'         => 'active',
                'last_synced_at' => now(),
            ]
        );
    }

    // ─────────────────────────────────────────────
    // Write an entry to carrier_sync_logs
    // ─────────────────────────────────────────────
    private function log(
        string  $rmisId,
        string  $action,
        ?string $message = null,
        ?string $timestamp = null
    ): void {
        CarrierSyncLog::create([
            'rmis_id'       => $rmisId,
            'action'        => $action,
            'message'       => $message,
            'api_timestamp' => $timestamp,
        ]);
    }
}
