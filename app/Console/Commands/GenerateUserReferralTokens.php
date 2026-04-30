<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateUserReferralTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate-referral-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate referral tokens for users who do not have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNull('referral_token')->get();
        $count = 0;

        foreach ($users as $user) {
            $user->generateReferralToken();
            $count++;
        }

        $this->info("Generated referral tokens for {$count} users.");
    }
}
