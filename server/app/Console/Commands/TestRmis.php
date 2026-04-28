<?php

namespace App\Console\Commands;

use App\Services\TruckstopRmisService;
use Illuminate\Console\Command;

class TestRmis extends Command
{
    protected $signature   = 'rmis:test';
    protected $description = 'Test Truckstop RMIS API with test carriers';

    public function handle(TruckstopRmisService $rmis): void
    {
        // 1. Check how many carriers are in the queue
        $this->info('Checking Delta queue summary...');
        $count = $rmis->getDeltaSummary();
        $this->line("Carriers in queue: $count");

        // 2. Test with your two test MC numbers
        foreach (['MC9999201', 'MC9979682'] as $mc) {
            $this->info("Fetching carrier: $mc");
            $result = $rmis->fetchCarrierByMC($mc);

            if (!$result) {
                $this->error("Failed to fetch $mc");
                continue;
            }

            $xml = $result['xml'];
            $this->line('  Name:   ' . ($xml->Carrier->Name ?? 'N/A'));
            $this->line('  DOT:    ' . ($xml->Carrier->USDOTNumber ?? 'N/A'));
            $this->line('  Status: ' . ($xml->Header->Result ?? 'N/A'));
            $this->line('  Timestamp: ' . $result['timestamp']);
        }

        // 3. Run a full Delta FETCH → EXPANDED → CLEAR cycle
        $this->info('Running full FETCH cycle...');
        $deltaCarriers = $rmis->fetchDeltaCarriers(10); // small batch for testing
        $this->line('Fetched: ' . count($deltaCarriers) . ' carriers from queue');

        $processed = [];
        foreach ($deltaCarriers as $delta) {
            $rmisId = $delta['InsdID'];
            $result = $rmis->fetchExpandedCarrier($rmisId);

            if ($result) {
                $this->line("  ✓ Got XML for RMIS ID: $rmisId");
                $processed[] = ['id' => $rmisId, 'timestamp' => $result['timestamp']];
            }
        }

        if (!empty($processed)) {
            $cleared = $rmis->clearDeltaCarriers($processed);
            $this->info($cleared ? '✓ CLEAR successful' : '✗ CLEAR failed');
        }

        $this->info('Test complete!');
    }
}