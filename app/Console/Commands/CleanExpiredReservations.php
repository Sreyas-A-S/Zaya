<?php

namespace App\Console\Commands;

use App\Models\BookingReservation;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:clean-expired-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired booking reservations (older than 15 minutes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = BookingReservation::where('status', 'reserved')
            ->where('expires_at', '<', now())
            ->delete();

        $this->info("Cleaned up {$deleted} expired reservations.");
        
        return 0;
    }
}
