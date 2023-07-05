<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\lots;
use Illuminate\Support\Facades\DB;

class CheckLotsStatus extends Command
{
    protected $signature = 'lots:check';

    protected $description = 'Check lots status and create jobs in the database';

    public function handle()
    {
        $activeLots = lots::whereIn('lot_status', ['active', 'upcoming'])->get();

        // Create jobs for active and upcoming lots
        foreach ($activeLots as $lot) {
            if ($lot->lot_status === 'active') {
                $status = 'active';
            } elseif ($lot->lot_status === 'upcoming') {
                $status = 'upcoming';
            }

            DB::table('jobs')->insert([
                'lot_id' => $lot->id,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->info('Lots status checked and jobs created successfully.');
    }
}
