<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Truckstop\ExpandedCarrierService;

class SyncCarriersCommand extends Command
{
    protected $signature   = 'carriers:sync {--test : Test with MC numbers only}';
    protected $description = 'Sync carriers from Truckstop API';

    public function handle(ExpandedCarrierService $carrierService)
    {
        // ✅ TEST MODE — run this first to verify credentials
        if ($this->option('test')) {
            $this->info('🔍 Testing with provided MC numbers...');

            $testMcNumbers = config('truckstop.test_mc_numbers');

            foreach ($testMcNumbers as $mc) {
                $this->info("Fetching: {$mc}");
                $result = $carrierService->getByMcNumber($mc);

                if (!$result) {
                    $this->error("❌ Failed to fetch {$mc} — check credentials or MC number");
                    continue;
                }

                if (isset($result['error']) && $result['error'] === 'detached') {
                    $this->warn("⚠️  {$mc} is detached from your account");
                    continue;
                }

                $this->info("✅ Success: {$result['company_name']}");
                $this->table(
                    ['Field', 'Value'],
                    collect($result)->map(fn($v, $k) => [$k, $v])->values()
                );
            }

            return Command::SUCCESS;
        }

        // 🔄 PRODUCTION MODE — full Delta flow (built in next steps)
        $this->info('Running full carrier sync...');
        dispatch(new \App\Jobs\SyncCarriersJob());
        $this->info('Job dispatched.');

        return Command::SUCCESS;
    }
}
